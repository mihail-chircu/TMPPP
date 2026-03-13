<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class FixSeederImages extends Command
{
    protected $signature = 'products:fix-seeder-images {--sleep-ms=1000}';
    protected $description = 'Search jucarenia.md for images matching seeder product names';

    // Map seeder product names to jucarenia search keywords
    private array $searchMap = [
        'Spider-Man Action Figure 30cm'   => 'spider man',
        'T-Rex Dinosaur Walking Toy'      => 'dinozaur',
        'Batman Dark Knight Figure'       => 'batman',
        'Transformers Optimus Prime'      => 'transformers',
        'Monopoly Classic Edition'        => 'monopoly',
        'UNO Card Game'                   => 'uno',
        'Scrabble Junior'                 => 'scrabble',
        'LEGO City Fire Station'          => 'lego city',
        'LEGO Friends Heartlake Cafe'     => 'lego friends',
        'Magnetic Building Tiles 100pc'   => 'magnetic',
        'LEGO Technic Race Car'           => 'lego technic',
        'Teddy Bear Premium 45cm'         => 'urs plus',
        'Baby Doll with Accessories'      => 'papusa',
        'Unicorn Plush Rainbow 35cm'      => 'unicorn plus',
        'Barbie Dreamhouse Set'           => 'barbie',
        'Kids Science Lab Kit'            => 'stiinta',
        'Wooden Alphabet Puzzle'          => 'puzzle lemn',
        'Interactive Globe Explorer'      => 'glob',
        'Kids Bicycle 16 inch'            => 'bicicleta copii',
        'Bubble Machine Deluxe'           => 'bule sapun',
        'Water Blaster Super Soaker'      => 'pistol apa',
        'World Map Puzzle 500pc'          => 'puzzle',
        'Disney Princess Puzzle 100pc'    => 'disney princess',
        'RC Racing Car 1:16'              => 'masina telecomanda',
        'RC Drone with Camera'            => 'drona',
        'RC Helicopter Indoor'            => 'elicopter telecomanda',
        'Kids Art Set 150 Pieces'         => 'set creatie',
        'Play-Doh Mega Set'              => 'play doh',
        'Jewelry Making Kit'              => 'set bijuterii',
        'Baby Activity Cube'              => 'cub activitati',
        'Stacking Rings Classic'          => 'piramida',
        'Musical Baby Walker'             => 'premergator',
        'Soft Rattle Set 4pc'             => 'zornaitoare',
    ];

    public function handle(): int
    {
        $products = Product::whereNull('source_url')
            ->with('category:id,name,slug')
            ->get();

        $this->info("Seeder products to fix: {$products->count()}");

        $fixed = 0;
        $failed = 0;
        $sleepMs = (int) $this->option('sleep-ms');

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $query = $this->searchMap[$product->name] ?? Str::slug($product->name, ' ');
            $imageUrl = $this->searchJucarenia($query);

            if ($imageUrl) {
                $downloaded = $this->downloadImage($imageUrl, $product);
                if ($downloaded) {
                    $fixed++;
                } else {
                    $failed++;
                    $this->line("\n  ✗ Download failed: {$product->name}");
                }
            } else {
                $failed++;
                $this->line("\n  ✗ No result: {$product->name} (q: {$query})");
            }

            $bar->advance();
            usleep($sleepMs * 1000);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Fixed: {$fixed} | Failed: {$failed}");

        return Command::SUCCESS;
    }

    private function searchJucarenia(string $query): ?string
    {
        $searchUrl = 'https://jucarenia.md/search-result?searchword=' . urlencode($query);

        $html = $this->fetch($searchUrl);
        if (!$html) return null;

        // Find first product link on search results
        $crawler = new Crawler($html);
        $productUrl = null;

        $crawler->filter('a[href*="/catalog/"]')->each(function (Crawler $node) use (&$productUrl) {
            if ($productUrl) return;
            $href = $node->attr('href');
            if (!$href) return;

            if (str_starts_with($href, '/')) {
                $href = 'https://jucarenia.md' . $href;
            }

            $path = parse_url($href, PHP_URL_PATH);
            if (!$path) return;

            // Must be deep enough to be a product (at least 4 segments)
            $segments = array_filter(explode('/', trim($path, '/')));
            if (count($segments) >= 3 && !str_contains($href, '?')) {
                $productUrl = $href;
            }
        });

        if (!$productUrl) return null;

        // Fetch product page and extract image
        usleep(500000); // 500ms between search and product page
        $productHtml = $this->fetch($productUrl);
        if (!$productHtml) return null;

        return $this->extractImageUrl($productHtml);
    }

    private function extractImageUrl(string $html): ?string
    {
        $url = null;

        // JSON-LD
        if (preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches)) {
            foreach ($matches[1] as $json) {
                $d = json_decode(trim($json), true);
                if ($d && ($d['@type'] ?? '') === 'Product' && !empty($d['image'])) {
                    $url = $d['image'];
                    break;
                }
            }
        }

        // Fallback: _medium.jpg
        if (!$url) {
            $crawler = new Crawler($html);
            $crawler->filter('img[src*="_medium.jpg"]')->each(function (Crawler $node) use (&$url) {
                if (!$url) {
                    $src = $node->attr('src');
                    if ($src && !str_contains($src, 'logo')) {
                        $url = $src;
                    }
                }
            });
        }

        if (!$url) return null;

        if (str_starts_with($url, '/')) {
            $url = 'https://jucarenia.md' . $url;
        }

        // Fix format
        if (!str_contains($url, '_medium.jpg') && preg_match('#(/images/x/)([^/]+)\.(jpg|jpeg|png|webp)#i', $url, $m)) {
            $url = 'https://jucarenia.md' . $m[1] . $m[2] . '/' . $m[2] . '_medium.jpg';
        }

        return $url;
    }

    private function downloadImage(string $imageUrl, Product $product): bool
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->withUserAgent('JucareniaImporter/1.0 (owner)')
                ->timeout(15)
                ->get($imageUrl);

            if (!$response->successful()) return false;

            $bytes = $response->body();
            if (strlen($bytes) < 1000) return false; // too small, probably error page

            $ext = 'jpg';
            $ct = $response->header('Content-Type');
            if (str_contains($ct, 'png'))  $ext = 'png';
            if (str_contains($ct, 'webp')) $ext = 'webp';

            $catSlug = $product->category?->slug ?? 'general';
            $prodSlug = Str::limit($product->slug, 80, '');
            $path = "products/{$catSlug}/{$prodSlug}/main.{$ext}";

            Storage::disk('public')->put($path, $bytes);

            // Update or create image record
            $img = ProductImage::where('product_id', $product->id)->where('is_primary', true)->first();
            if ($img) {
                $img->update(['path' => $path, 'alt' => $product->name]);
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
