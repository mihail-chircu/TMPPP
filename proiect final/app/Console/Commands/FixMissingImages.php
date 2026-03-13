<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class FixMissingImages extends Command
{
    protected $signature = 'products:fix-images
        {--sleep-ms=800 : Milliseconds between requests}';

    protected $description = 'Re-download images for products that have a source_url but no valid image';

    private array $stats = ['fixed' => 0, 'failed' => 0, 'skipped' => 0];

    public function handle(): int
    {
        // 1) Products with source_url but no image record at all
        $noRecord = Product::whereNotNull('source_url')
            ->where('source_url', '!=', '')
            ->whereDoesntHave('images')
            ->with('category:id,name,slug')
            ->get();

        // 2) Products with source_url whose primary image file is missing
        $missingFile = collect();
        ProductImage::where('is_primary', true)
            ->whereHas('product', fn($q) => $q->whereNotNull('source_url')->where('source_url', '!=', ''))
            ->with('product.category:id,name,slug')
            ->cursor()
            ->each(function (ProductImage $img) use ($missingFile) {
                if (empty($img->path) || !Storage::disk('public')->exists($img->path)) {
                    $missingFile->push($img->product);
                }
            });

        $all = $noRecord->merge($missingFile)->unique('id');

        $this->info("Products to fix: {$all->count()}");
        $this->info("  No image record: {$noRecord->count()}");
        $this->info("  File missing on disk: {$missingFile->count()}");

        if ($all->isEmpty()) {
            $this->info('Nothing to fix!');
            return Command::SUCCESS;
        }

        $this->newLine();
        $bar = $this->output->createProgressBar($all->count());
        $bar->start();

        foreach ($all as $product) {
            $this->fixProduct($product);
            $bar->advance();
            usleep((int) $this->option('sleep-ms') * 1000);
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(['Metric', 'Count'], [
            ['Fixed', $this->stats['fixed']],
            ['Failed', $this->stats['failed']],
            ['Skipped (no source)', $this->stats['skipped']],
        ]);

        return Command::SUCCESS;
    }

    private function fixProduct(Product $product): void
    {
        if (empty($product->source_url)) {
            $this->stats['skipped']++;
            return;
        }

        // Fetch product page
        $html = $this->fetchPage($product->source_url);
        if (!$html) {
            $this->stats['failed']++;
            $this->line("\n  ✗ Fetch failed: {$product->name}");
            return;
        }

        // Find image URL
        $imageUrl = $this->findImageUrl($html);
        if (!$imageUrl) {
            $this->stats['failed']++;
            $this->line("\n  ✗ No image found: {$product->name}");
            return;
        }

        // Download image
        $categorySlug = $product->category?->slug ?? 'uncategorized';
        $productSlug = Str::limit($product->slug, 80, '');

        $downloaded = $this->downloadImage($imageUrl, $product, $categorySlug, $productSlug);

        if ($downloaded) {
            $this->stats['fixed']++;
        } else {
            $this->stats['failed']++;
            $this->line("\n  ✗ Download failed: {$product->name}");
        }
    }

    private function findImageUrl(string $html): ?string
    {
        // Strategy 1: JSON-LD
        $url = $this->fromJsonLd($html);

        // Strategy 2: HTML img tags with _medium.jpg
        if (!$url) {
            $crawler = new Crawler($html);
            $crawler->filter('img[src*="_medium.jpg"]')->each(function (Crawler $node) use (&$url) {
                if (!$url) {
                    $src = $node->attr('src');
                    if ($src && !str_contains($src, 'logo') && !str_contains($src, 'icon')) {
                        $url = $src;
                    }
                }
            });
        }

        // Strategy 3: og:image
        if (!$url) {
            $crawler = $crawler ?? new Crawler($html);
            try {
                $node = $crawler->filter('meta[property="og:image"]')->first();
                if ($node->count()) {
                    $url = $node->attr('content');
                }
            } catch (\Throwable $e) {}
        }

        // Strategy 4: Any product image
        if (!$url) {
            $crawler = $crawler ?? new Crawler($html);
            $crawler->filter('img[src*="/images/"]')->each(function (Crawler $node) use (&$url) {
                if (!$url) {
                    $src = $node->attr('src');
                    if ($src && !str_contains($src, 'logo') && !str_contains($src, 'icon')) {
                        $url = $src;
                    }
                }
            });
        }

        if (!$url) return null;

        // Make absolute
        if (str_starts_with($url, '/')) {
            $url = 'https://jucarenia.md' . $url;
        }

        // Fix jucarenia format: /images/x/CODE.jpg -> /images/x/CODE/CODE_medium.jpg
        $url = $this->fixImageUrl($url);

        return $url;
    }

    private function fromJsonLd(string $html): ?string
    {
        if (!preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches)) {
            return null;
        }

        foreach ($matches[1] as $json) {
            $decoded = json_decode(trim($json), true);
            if (!$decoded) continue;

            if (($decoded['@type'] ?? '') === 'Product' && !empty($decoded['image'])) {
                return $decoded['image'];
            }

            if (isset($decoded['@graph'])) {
                foreach ($decoded['@graph'] as $item) {
                    if (($item['@type'] ?? '') === 'Product' && !empty($item['image'])) {
                        return $item['image'];
                    }
                }
            }
        }

        return null;
    }

    private function fixImageUrl(string $url): string
    {
        if (str_contains($url, '_medium.jpg') || str_contains($url, '_big.jpg')) {
            return $url;
        }

        if (preg_match('#(/images/x/)([^/]+)\.(jpg|jpeg|png|webp)#i', $url, $m)) {
            return 'https://jucarenia.md' . $m[1] . $m[2] . '/' . $m[2] . '_medium.jpg';
        }

        return $url;
    }

    private function downloadImage(string $imageUrl, Product $product, string $categorySlug, string $productSlug): bool
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->withUserAgent('JucareniaImporter/1.0 (owner)')
                ->timeout(15)
                ->get($imageUrl);

            if (!$response->successful()) {
                return false;
            }

            $bytes = $response->body();
            if (strlen($bytes) > 8 * 1024 * 1024) {
                return false;
            }

            $ext = 'jpg';
            $ct = $response->header('Content-Type');
            if (str_contains($ct, 'png'))  $ext = 'png';
            if (str_contains($ct, 'webp')) $ext = 'webp';

            $path = "products/{$categorySlug}/{$productSlug}/main.{$ext}";
            Storage::disk('public')->put($path, $bytes);

            // Upsert primary image record
            $existing = ProductImage::where('product_id', $product->id)
                ->where('is_primary', true)
                ->first();

            if ($existing) {
                $existing->update(['path' => $path, 'alt' => $product->name]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => $path,
                    'alt'        => $product->name,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function fetchPage(string $url): ?string
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->withUserAgent('JucareniaImporter/1.0 (owner)')
                ->timeout(10)
                ->retry(2, 500)
                ->get($url);

            return $response->successful() ? $response->body() : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
