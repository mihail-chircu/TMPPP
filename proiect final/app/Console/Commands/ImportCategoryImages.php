<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ImportCategoryImages extends Command
{
    protected $signature = 'categories:import-images {--sleep-ms=800} {--force=0 : Re-download even if image exists}';
    protected $description = 'Download category images from jucarenia.md source pages';

    public function handle(): int
    {
        $force = (bool) $this->option('force');
        $sleepMs = (int) $this->option('sleep-ms');

        $categories = Category::whereNotNull('source_url')
            ->where('source_url', '!=', '')
            ->when(!$force, fn($q) => $q->where(fn($q2) => $q2->whereNull('image')->orWhere('image', '')))
            ->get();

        $this->info("Categories to process: {$categories->count()}");

        if ($categories->isEmpty()) {
            $this->info('All categories already have images.');
            return Command::SUCCESS;
        }

        $fixed = 0;
        $failed = 0;
        $bar = $this->output->createProgressBar($categories->count());
        $bar->start();

        foreach ($categories as $category) {
            $imageUrl = $this->findCategoryImage($category->source_url);

            if ($imageUrl && $this->downloadImage($imageUrl, $category)) {
                $fixed++;
            } else {
                // Fallback: use first product's image from this category
                $fallbackUrl = $this->firstProductImage($category);
                if ($fallbackUrl && $this->downloadImage($fallbackUrl, $category)) {
                    $fixed++;
                } else {
                    $failed++;
                    $this->line("\n  ✗ {$category->name}");
                }
            }

            $bar->advance();
            usleep($sleepMs * 1000);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Fixed: {$fixed} | Failed: {$failed}");

        return Command::SUCCESS;
    }

    private function findCategoryImage(string $categoryUrl): ?string
    {
        $html = $this->fetch($categoryUrl);
        if (!$html) return null;

        $crawler = new Crawler($html);

        // Strategy 1: First product card image (_medium.jpg)
        $url = null;
        $crawler->filter('img[src*="_medium.jpg"]')->each(function (Crawler $node) use (&$url) {
            if ($url) return;
            $src = $node->attr('src');
            if ($src && !str_contains($src, 'logo') && !str_contains($src, 'icon') && !str_contains($src, 'banner')) {
                $url = $src;
            }
        });

        // Strategy 2: Any product image in /images/x/
        if (!$url) {
            $crawler->filter('img[src*="/images/x/"]')->each(function (Crawler $node) use (&$url) {
                if ($url) return;
                $src = $node->attr('src');
                if ($src && !str_contains($src, 'logo')) {
                    $url = $src;
                }
            });
        }

        // Strategy 3: og:image
        if (!$url) {
            try {
                $node = $crawler->filter('meta[property="og:image"]')->first();
                if ($node->count()) {
                    $url = $node->attr('content');
                }
            } catch (\Throwable $e) {}
        }

        if (!$url) return null;

        // Make absolute
        if (str_starts_with($url, '/')) {
            $url = 'https://jucarenia.md' . $url;
        }

        return $url;
    }

    private function firstProductImage(Category $category): ?string
    {
        // Get first product that has an image
        $product = $category->products()
            ->whereHas('primaryImage')
            ->with('primaryImage')
            ->first();

        if ($product && $product->primaryImage && Storage::disk('public')->exists($product->primaryImage->path)) {
            // We already have this image locally — just copy it
            return 'local:' . $product->primaryImage->path;
        }

        // Try fetching from source_url of first product
        $product = $category->products()->whereNotNull('source_url')->first();
        if (!$product) return null;

        $html = $this->fetch($product->source_url);
        if (!$html) return null;

        // Extract image from JSON-LD
        if (preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches)) {
            foreach ($matches[1] as $json) {
                $d = json_decode(trim($json), true);
                if ($d && ($d['@type'] ?? '') === 'Product' && !empty($d['image'])) {
                    $imgUrl = $d['image'];
                    if (str_starts_with($imgUrl, '/')) $imgUrl = 'https://jucarenia.md' . $imgUrl;
                    // Fix format
                    if (!str_contains($imgUrl, '_medium.jpg') && preg_match('#(/images/x/)([^/]+)\.(jpg|jpeg|png|webp)#i', $imgUrl, $m)) {
                        $imgUrl = 'https://jucarenia.md' . $m[1] . $m[2] . '/' . $m[2] . '_medium.jpg';
                    }
                    return $imgUrl;
                }
            }
        }

        return null;
    }

    private function downloadImage(string $imageUrl, Category $category): bool
    {
        try {
            $slug = $category->slug ?: Str::slug($category->name);
            $path = "categories/{$slug}/cover.jpg";

            // Handle local copy
            if (str_starts_with($imageUrl, 'local:')) {
                $sourcePath = substr($imageUrl, 6);
                $bytes = Storage::disk('public')->get($sourcePath);
                if (!$bytes) return false;
                Storage::disk('public')->put($path, $bytes);
                $category->update(['image' => $path]);
                return true;
            }

            $response = Http::withOptions(['verify' => false])
                ->withUserAgent('JucareniaImporter/1.0 (owner)')
                ->timeout(15)
                ->get($imageUrl);

            if (!$response->successful()) return false;

            $bytes = $response->body();
            if (strlen($bytes) < 500 || strlen($bytes) > 8 * 1024 * 1024) return false;

            $ext = 'jpg';
            $ct = $response->header('Content-Type');
            if (str_contains($ct, 'png'))  $ext = 'png';
            if (str_contains($ct, 'webp')) $ext = 'webp';
            $path = "categories/{$slug}/cover.{$ext}";

            Storage::disk('public')->put($path, $bytes);
            $category->update(['image' => $path]);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function fetch(string $url): ?string
    {
        try {
            $r = Http::withOptions(['verify' => false])
                ->withUserAgent('JucareniaImporter/1.0 (owner)')
                ->timeout(10)
                ->retry(2, 500)
                ->get($url);
            return $r->successful() ? $r->body() : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
