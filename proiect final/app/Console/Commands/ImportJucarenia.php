<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ImportJucarenia extends Command
{
    protected $signature = 'import:jucarenia
        {--download-images=1 : Download product images locally}
        {--sleep-ms=1200 : Milliseconds to sleep between requests}
        {--shuffle=1 : Shuffle product URLs before selecting}
        {--dry-run=0 : Preview without saving}';

    protected $description = 'Import categories and products from jucarenia.md';

    private int $sleepMs;
    private bool $downloadImages;
    private bool $shuffle;
    private bool $dryRun;

    private array $stats = [
        'categories_created' => 0,
        'categories_updated' => 0,
        'products_created'   => 0,
        'products_updated'   => 0,
        'images_downloaded'  => 0,
        'errors'             => [],
    ];

    private array $categoryPlan = [
        // ── Jucării ──
        ['url' => 'https://jucarenia.md/catalog/jucarii/jucarii-din-plu', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/jucarii/brand-uri-populare/lego', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/jucarii/arme-i-accesorii', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/jucarii/seturi-de-creaie', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/jucarii/personaje-si-figurine', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/jucarii/jucarii-cu-telecomanda/maini-cu-telecomanda', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/jucarii/jucarii-cu-telecomanda/drone-i-elicoptere', 'limit' => 15],
        ['url' => 'https://jucarenia.md/catalog/jucarii/jucarii-antistres', 'limit' => 15],
        ['url' => 'https://jucarenia.md/catalog/jucarii/jucarii-educative', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/jucarii/trenuri-si-vehicule', 'limit' => 15],
        ['url' => 'https://jucarenia.md/catalog/jucarii/papusi-si-case-pentru-papusi', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/jucarii/instrumente-muzicale', 'limit' => 15],
        ['url' => 'https://jucarenia.md/catalog/jucarii/seturi-de-joaca/frumusee-i-stil', 'limit' => 15],
        ['url' => 'https://jucarenia.md/catalog/jucarii/seturi-de-joaca/experimente-stiintifice', 'limit' => 15],
        // ── Jocuri în aer liber ──
        ['url' => 'https://jucarenia.md/catalog/jocuri-in-aer-liber/transport-pentru-copii/masini-electrice', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/jocuri-in-aer-liber/transport-pentru-copii/biciclete', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/jocuri-in-aer-liber/transport-pentru-copii/tolocare-premergatori', 'limit' => 15],
        ['url' => 'https://jucarenia.md/catalog/jocuri-in-aer-liber/transport-pentru-copii/trotinete', 'limit' => 15],
        ['url' => 'https://jucarenia.md/catalog/jocuri-in-aer-liber/bazibe', 'limit' => 15],
        ['url' => 'https://jucarenia.md/catalog/jocuri-in-aer-liber/trambuline', 'limit' => 10],
        ['url' => 'https://jucarenia.md/catalog/jocuri-in-aer-liber/jocuri-sportive', 'limit' => 15],
        // ── Puzzle-uri și jocuri de societate ──
        ['url' => 'https://jucarenia.md/catalog/puzzle-uri-i-jocuri-de-societate/puzzle-uri', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/puzzle-uri-i-jocuri-de-societate/jocuri-de-societate-pentru-copii', 'limit' => 20],
        ['url' => 'https://jucarenia.md/catalog/puzzle-uri-i-jocuri-de-societate/jocuri-de-societate-populare', 'limit' => 15],
        ['url' => 'https://jucarenia.md/catalog/puzzle-uri-i-jocuri-de-societate/3d-puzzle', 'limit' => 10],
    ];

    public function handle(): int
    {
        $this->sleepMs = (int) $this->option('sleep-ms');
        $this->downloadImages = (bool) $this->option('download-images');
        $this->shuffle = (bool) $this->option('shuffle');
        $this->dryRun = (bool) $this->option('dry-run');

        // Ensure storage link
        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        $this->info("Starting jucarenia.md import...");
        $this->info("Sleep: {$this->sleepMs}ms | Images: " . ($this->downloadImages ? 'yes' : 'no') . " | Shuffle: " . ($this->shuffle ? 'yes' : 'no'));
        $this->newLine();

        foreach ($this->categoryPlan as $i => $plan) {
            $num = $i + 1;
            $this->info("━━━ [{$num}/" . count($this->categoryPlan) . "] {$plan['url']} (limit: {$plan['limit']}) ━━━");
            $this->processCategory($plan['url'], $plan['limit']);
            $this->newLine();
        }

        $this->printSummary();
        $this->writeLog();

        return Command::SUCCESS;
    }

    private function processCategory(string $categoryUrl, int $limit): void
    {
        // 1. Fetch category page
        $html = $this->fetchPage($categoryUrl);
        if (!$html) {
            $this->logError("Failed to fetch category: {$categoryUrl}");
            return;
        }

        // 2. Parse category name
        $crawler = new Crawler($html);
        $categoryName = $this->parseCategoryName($crawler, $categoryUrl);
        $categorySlug = Str::slug($categoryName);

        $this->info("  Category: {$categoryName} ({$categorySlug})");

        // 3. Upsert category
        $category = Category::updateOrCreate(
            ['source_url' => $categoryUrl],
            [
                'name'      => $categoryName,
                'slug'      => $this->uniqueSlug('categories', $categorySlug, 'source_url', $categoryUrl),
                'is_active' => true,
            ]
        );

        if ($category->wasRecentlyCreated) {
            $this->stats['categories_created']++;
            $this->info("  → Category created (ID: {$category->id})");
        } else {
            $this->stats['categories_updated']++;
            $this->info("  → Category updated (ID: {$category->id})");
        }

        // 4. Collect product URLs across pages
        $productUrls = $this->collectProductUrls($categoryUrl, $html, $limit);

        if (count($productUrls) < $limit) {
            $this->warn("  ⚠ Only found " . count($productUrls) . " products (requested {$limit})");
        }

        $this->info("  Importing " . count($productUrls) . " products...");

        // 5. Import each product
        $bar = $this->output->createProgressBar(count($productUrls));
        $bar->start();

        foreach ($productUrls as $productUrl) {
            try {
                $this->importProduct($productUrl, $category);
            } catch (\Throwable $e) {
                $this->logError("Product error [{$productUrl}]: {$e->getMessage()}");
            }
            $bar->advance();
            usleep($this->sleepMs * 1000);
        }

        $bar->finish();
        $this->newLine();
    }

    private function collectProductUrls(string $categoryUrl, string $firstPageHtml, int $limit): array
    {
        $allUrls = [];
        $page = 1;
        $maxPages = 20;
        $html = $firstPageHtml;

        while (count($allUrls) < $limit && $page <= $maxPages) {
            $crawler = new Crawler($html);

            // Extract product links from this page
            $pageUrls = $this->extractProductUrls($crawler, $categoryUrl);
            $allUrls = array_unique(array_merge($allUrls, $pageUrls));

            $this->line("  Page {$page}: found " . count($pageUrls) . " links (total: " . count($allUrls) . ")");

            if (count($allUrls) >= $limit) {
                break;
            }

            // Check for next page
            $nextUrl = $this->findNextPage($crawler, $categoryUrl, $page);
            if (!$nextUrl) {
                break;
            }

            $page++;
            usleep($this->sleepMs * 1000);
            $html = $this->fetchPage($nextUrl);
            if (!$html) {
                break;
            }
        }

        // Shuffle if needed, then take exactly $limit
        if ($this->shuffle) {
            shuffle($allUrls);
        }

        return array_slice($allUrls, 0, $limit);
    }

    private function extractProductUrls(Crawler $crawler, string $categoryUrl): array
    {
        $urls = [];
        $basePath = parse_url($categoryUrl, PHP_URL_PATH);

        // Strategy 1: Links that are deeper than the category URL path
        $crawler->filter('a[href]')->each(function (Crawler $node) use (&$urls, $basePath) {
            $href = $node->attr('href');
            if (!$href) return;

            // Make absolute
            if (str_starts_with($href, '/')) {
                $href = 'https://jucarenia.md' . $href;
            }

            // Must be on jucarenia.md
            if (!str_contains($href, 'jucarenia.md')) return;

            $path = parse_url($href, PHP_URL_PATH);
            if (!$path) return;

            // Product URL is deeper than category (has more segments)
            // and starts with the category base path
            if (str_starts_with($path, $basePath . '/') && $path !== $basePath . '/') {
                $subPath = substr($path, strlen($basePath) + 1);
                // Product URLs don't have further slashes (they're direct children)
                if (!str_contains($subPath, '/') && strlen($subPath) > 5) {
                    $urls[] = $href;
                }
            }
        });

        return array_unique($urls);
    }

    private function findNextPage(Crawler $crawler, string $categoryUrl, int $currentPage): ?string
    {
        $nextPage = $currentPage + 1;

        // Look for ?page=N link
        $nextUrl = null;
        $crawler->filter('a[href]')->each(function (Crawler $node) use (&$nextUrl, $nextPage, $categoryUrl) {
            $href = $node->attr('href');
            if ($href && str_contains($href, "page={$nextPage}")) {
                if (str_starts_with($href, '/')) {
                    $href = 'https://jucarenia.md' . $href;
                } elseif (str_starts_with($href, '?')) {
                    $href = $categoryUrl . $href;
                }
                $nextUrl = $href;
            }
        });

        // Also try › arrow link
        if (!$nextUrl) {
            $crawler->filter('a[href]')->each(function (Crawler $node) use (&$nextUrl, $categoryUrl) {
                $text = trim($node->text(''));
                if (in_array($text, ['›', '»', 'Next', 'Următoarea'])) {
                    $href = $node->attr('href');
                    if ($href) {
                        if (str_starts_with($href, '/')) {
                            $href = 'https://jucarenia.md' . $href;
                        } elseif (str_starts_with($href, '?')) {
                            $href = $categoryUrl . $href;
                        }
                        $nextUrl = $href;
                    }
                }
            });
        }

        return $nextUrl;
    }

    private function importProduct(string $productUrl, Category $category): void
    {
        $html = $this->fetchPage($productUrl);
        if (!$html) {
            $this->logError("Failed to fetch product: {$productUrl}");
            return;
        }

        $crawler = new Crawler($html);
        $data = $this->parseProduct($crawler, $html, $productUrl);

        if (!$data['name']) {
            $this->logError("No product name found: {$productUrl}");
            return;
        }

        $slug = Str::slug($data['name']);
        if (strlen($slug) > 200) {
            $slug = substr($slug, 0, 200);
        }

        if ($this->dryRun) {
            $this->line("    [DRY] {$data['name']} — {$data['price']} {$data['currency']}");
            return;
        }

        // Ensure SKU uniqueness — append slug fragment if collision
        $sku = $data['sku'];
        if ($sku) {
            $skuConflict = Product::where('sku', $sku)
                ->where('source_url', '!=', $productUrl)
                ->exists();
            if ($skuConflict) {
                $sku = $sku . '-' . substr(md5($productUrl), 0, 6);
            }
        }

        // Upsert product by source_url
        $product = Product::updateOrCreate(
            ['source_url' => $productUrl],
            [
                'name'              => $data['name'],
                'slug'              => $this->uniqueSlug('products', $slug, 'source_url', $productUrl),
                'description'       => $data['description'],
                'short_description' => $data['short_description'],
                'price'             => $data['price'] ?? 0,
                'currency'          => $data['currency'] ?? 'MDL',
                'sku'               => $sku,
                'category_id'       => $category->id,
                'brand'             => $data['brand'],
                'stock'             => 10,
                'is_active'         => true,
                'badge'             => 'new',
            ]
        );

        if ($product->wasRecentlyCreated) {
            $this->stats['products_created']++;
        } else {
            $this->stats['products_updated']++;
        }

        // Download and save main image
        if ($this->downloadImages && $data['image_url']) {
            $this->downloadProductImage($product, $data['image_url'], $category->slug ?? Str::slug($category->name));
        }
    }

    private function parseProduct(Crawler $crawler, string $html, string $url): array
    {
        $data = [
            'name'              => null,
            'price'             => null,
            'currency'          => 'MDL',
            'description'       => null,
            'short_description' => null,
            'image_url'         => null,
            'sku'               => null,
            'brand'             => null,
        ];

        // --- Try JSON-LD first (most reliable) ---
        $jsonLd = $this->extractJsonLd($html);
        if ($jsonLd) {
            $data['name']  = $jsonLd['name'] ?? null;
            $data['image_url'] = $jsonLd['image'] ?? null;
            $data['sku']   = $jsonLd['sku'] ?? $jsonLd['mpn'] ?? null;
            $data['brand'] = is_array($jsonLd['brand'] ?? null)
                ? ($jsonLd['brand']['name'] ?? null)
                : ($jsonLd['brand'] ?? null);

            // Description from JSON-LD
            $data['description'] = $jsonLd['description'] ?? null;

            // Price from offers
            $offers = $jsonLd['offers'] ?? null;
            if (is_array($offers)) {
                // Could be a single offer or array of offers
                $offer = isset($offers['price']) ? $offers : ($offers[0] ?? null);
                if ($offer) {
                    $data['price']    = (float) ($offer['price'] ?? 0);
                    $data['currency'] = $offer['priceCurrency'] ?? 'MDL';
                }
            }
        }

        // --- Fallback: HTML parsing ---

        // Name fallback
        if (!$data['name']) {
            $data['name'] = $this->crawlerText($crawler, 'h1');
        }

        // Price fallback from HTML text
        if (!$data['price']) {
            $priceText = $this->crawlerText($crawler, '.price, [itemprop="price"], .product-price');
            if ($priceText && preg_match('/[\d\s,.]+/', $priceText, $m)) {
                $data['price'] = (float) str_replace([' ', ','], ['', '.'], trim($m[0]));
            }
        }

        // Image fallback - try multiple strategies
        if (!$data['image_url']) {
            // Try og:image
            $ogImage = $this->crawlerAttr($crawler, 'meta[property="og:image"]', 'content');
            if ($ogImage) {
                $data['image_url'] = $ogImage;
            }
        }

        if (!$data['image_url']) {
            // Try _medium.jpg images from HTML (the working format)
            $crawler->filter('img[src*="_medium.jpg"]')->each(function (Crawler $node) use (&$data) {
                if (!$data['image_url']) {
                    $src = $node->attr('src');
                    if ($src && !str_contains($src, 'logo') && !str_contains($src, 'icon')) {
                        $data['image_url'] = $src;
                    }
                }
            });
        }

        if (!$data['image_url']) {
            // Try any product image
            $crawler->filter('img[src*="/images/"]')->each(function (Crawler $node) use (&$data) {
                if (!$data['image_url']) {
                    $src = $node->attr('src');
                    if ($src && !str_contains($src, 'logo') && !str_contains($src, 'icon')) {
                        $data['image_url'] = $src;
                    }
                }
            });
        }

        // Make image URL absolute
        if ($data['image_url'] && str_starts_with($data['image_url'], '/')) {
            $data['image_url'] = 'https://jucarenia.md' . $data['image_url'];
        }

        // Fix jucarenia image URL format:
        // JSON-LD gives: /images/x/CODE_02.jpg
        // Working URL:   /images/x/CODE_02/CODE_02_medium.jpg
        if ($data['image_url']) {
            $data['image_url'] = $this->fixImageUrl($data['image_url']);
        }

        // SKU fallback
        if (!$data['sku']) {
            $crawler->filter('*')->each(function (Crawler $node) use (&$data) {
                $text = $node->text('');
                if (!$data['sku'] && preg_match('/Articu[lo]l?\s*:?\s*(.+)/iu', $text, $m)) {
                    $data['sku'] = trim($m[1]);
                    if (strlen($data['sku']) > 50) $data['sku'] = null; // too long, probably wrong match
                }
            });
        }

        // Short description (first 255 chars of description)
        if ($data['description']) {
            $data['short_description'] = Str::limit(strip_tags($data['description']), 250);
        }

        return $data;
    }

    private function extractJsonLd(string $html): ?array
    {
        // Find all JSON-LD blocks
        if (!preg_match_all('/<script[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/si', $html, $matches)) {
            return null;
        }

        foreach ($matches[1] as $json) {
            $decoded = json_decode(trim($json), true);
            if (!$decoded) continue;

            // Check for @type Product
            if (($decoded['@type'] ?? '') === 'Product') {
                return $decoded;
            }

            // Check for @graph containing a Product
            if (isset($decoded['@graph'])) {
                foreach ($decoded['@graph'] as $item) {
                    if (($item['@type'] ?? '') === 'Product') {
                        return $item;
                    }
                }
            }
        }

        return null;
    }

    private function downloadProductImage(Product $product, string $imageUrl, string $categorySlug): void
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->withUserAgent('JucareniaImporter/1.0 (owner)')
                ->timeout(15)
                ->get($imageUrl);

            if (!$response->successful()) {
                $this->logError("Image download failed ({$response->status()}): {$imageUrl}");
                return;
            }

            $bytes = $response->body();
            if (strlen($bytes) > 8 * 1024 * 1024) {
                $this->logError("Image too large (>8MB): {$imageUrl}");
                return;
            }

            // Determine extension
            $ext = 'jpg';
            $contentType = $response->header('Content-Type');
            if (str_contains($contentType, 'png'))  $ext = 'png';
            if (str_contains($contentType, 'webp')) $ext = 'webp';

            $productSlug = $product->slug;
            if (strlen($productSlug) > 80) {
                $productSlug = substr($productSlug, 0, 80);
            }

            $path = "products/{$categorySlug}/{$productSlug}/main.{$ext}";
            Storage::disk('public')->put($path, $bytes);

            // Upsert primary image record
            $existingImage = ProductImage::where('product_id', $product->id)
                ->where('is_primary', true)
                ->first();

            if ($existingImage) {
                $existingImage->update(['path' => $path]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => $path,
                    'alt'        => $product->name,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }

            $this->stats['images_downloaded']++;
        } catch (\Throwable $e) {
            $this->logError("Image error [{$imageUrl}]: {$e->getMessage()}");
        }
    }

    /**
     * Fix jucarenia image URL format.
     * JSON-LD: https://jucarenia.md/images/x/CODE.jpg
     * Working: https://jucarenia.md/images/x/CODE/CODE_medium.jpg
     */
    private function fixImageUrl(string $url): string
    {
        // Already in correct format
        if (str_contains($url, '_medium.jpg') || str_contains($url, '_big.jpg')) {
            return $url;
        }

        // Pattern: /images/x/SOMETHING.jpg -> /images/x/SOMETHING/SOMETHING_medium.jpg
        if (preg_match('#(/images/x/)([^/]+)\.(jpg|jpeg|png|webp)#i', $url, $m)) {
            $base = $m[1];
            $code = $m[2];
            $newUrl = 'https://jucarenia.md' . $base . $code . '/' . $code . '_medium.jpg';
            return $newUrl;
        }

        return $url;
    }

    // ─── Helpers ─────────────────────────────────────────

    private function fetchPage(string $url): ?string
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->withUserAgent('JucareniaImporter/1.0 (owner)')
                ->timeout(10)
                ->retry(2, 500)
                ->get($url);

            if ($response->successful()) {
                return $response->body();
            }

            $this->logError("HTTP {$response->status()} for: {$url}");
            return null;
        } catch (\Throwable $e) {
            $this->logError("Fetch error [{$url}]: {$e->getMessage()}");
            return null;
        }
    }

    private function parseCategoryName(Crawler $crawler, string $url): string
    {
        // Try H1
        $h1 = $this->crawlerText($crawler, 'h1');
        if ($h1 && strlen($h1) > 2 && strlen($h1) < 200) {
            return $h1;
        }

        // Fallback: last URL segment prettified
        $path = parse_url($url, PHP_URL_PATH);
        $segment = basename($path);
        return ucfirst(str_replace(['-', '_'], ' ', $segment));
    }

    private function crawlerText(Crawler $crawler, string $selector): ?string
    {
        try {
            $node = $crawler->filter($selector)->first();
            if ($node->count()) {
                $text = trim($node->text(''));
                return $text ?: null;
            }
        } catch (\Throwable $e) {
        }
        return null;
    }

    private function crawlerAttr(Crawler $crawler, string $selector, string $attr): ?string
    {
        try {
            $node = $crawler->filter($selector)->first();
            if ($node->count()) {
                return $node->attr($attr);
            }
        } catch (\Throwable $e) {
        }
        return null;
    }

    private function uniqueSlug(string $table, string $slug, string $sourceUrlCol, string $sourceUrl): string
    {
        $existing = \DB::table($table)
            ->where('slug', $slug)
            ->where($sourceUrlCol, '!=', $sourceUrl)
            ->exists();

        if (!$existing) {
            return $slug;
        }

        // Append a short hash
        return $slug . '-' . substr(md5($sourceUrl), 0, 6);
    }

    private function logError(string $message): void
    {
        $this->error("  ✗ {$message}");
        $this->stats['errors'][] = $message;
    }

    private function printSummary(): void
    {
        $this->newLine();
        $this->info('━━━ IMPORT SUMMARY ━━━');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Categories created', $this->stats['categories_created']],
                ['Categories updated', $this->stats['categories_updated']],
                ['Products created', $this->stats['products_created']],
                ['Products updated', $this->stats['products_updated']],
                ['Images downloaded', $this->stats['images_downloaded']],
                ['Errors', count($this->stats['errors'])],
            ]
        );

        if ($this->stats['errors']) {
            $this->warn('Errors:');
            foreach (array_slice($this->stats['errors'], 0, 20) as $err) {
                $this->line("  - {$err}");
            }
        }
    }

    private function writeLog(): void
    {
        $log = [
            'timestamp'  => now()->toIso8601String(),
            'stats'      => $this->stats,
        ];

        $path = storage_path('logs/import-jucarenia-' . now()->format('Y-m-d_His') . '.json');
        file_put_contents($path, json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->info("Log written to: {$path}");
    }
}
