<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CleanupProductsWithoutImages extends Command
{
    protected $signature = 'products:cleanup-images
        {--dry-run=1 : Preview only, do not delete}
        {--check-files=1 : Also flag products whose image file is missing from disk}
        {--limit=0 : Max products to delete (0 = unlimited)}';

    protected $description = 'Delete products that have no valid image (no image record or file missing on disk)';

    public function handle(): int
    {
        $dryRun     = (bool) $this->option('dry-run');
        $checkFiles = (bool) $this->option('check-files');
        $limit      = (int) $this->option('limit');

        $this->info($dryRun ? '=== DRY RUN ===' : '=== LIVE RUN ===');
        $this->newLine();

        $noImageIds   = [];
        $missingFileIds = [];

        // 1) Products with zero image records
        $noImageIds = Product::whereDoesntHave('images')->pluck('id')->all();
        $this->info("Products with no image record: " . count($noImageIds));

        // 2) Products whose image file doesn't exist on disk
        if ($checkFiles) {
            $this->info("Checking files on disk...");
            $bar = $this->output->createProgressBar(
                ProductImage::where('is_primary', true)->count()
            );

            ProductImage::where('is_primary', true)
                ->with('product:id,name')
                ->cursor()
                ->each(function (ProductImage $img) use (&$missingFileIds, $bar) {
                    $bar->advance();

                    if (empty($img->path)) {
                        $missingFileIds[] = $img->product_id;
                        return;
                    }

                    if (!Storage::disk('public')->exists($img->path)) {
                        $missingFileIds[] = $img->product_id;
                    }
                });

            $bar->finish();
            $this->newLine();
            $this->info("Products with missing file on disk: " . count($missingFileIds));
        }

        // Combine & deduplicate
        $allIds = array_unique(array_merge($noImageIds, $missingFileIds));

        if ($limit > 0) {
            $allIds = array_slice($allIds, 0, $limit);
        }

        $this->newLine();
        $this->info("Total products to delete: " . count($allIds));

        if (empty($allIds)) {
            $this->info("Nothing to clean up.");
            return Command::SUCCESS;
        }

        // Preview table (first 30)
        $preview = Product::whereIn('id', array_slice($allIds, 0, 30))
            ->get(['id', 'name', 'slug', 'category_id', 'source_url']);

        $this->newLine();
        $this->table(
            ['ID', 'Name', 'Reason'],
            $preview->map(function (Product $p) use ($noImageIds, $missingFileIds) {
                $reasons = [];
                if (in_array($p->id, $noImageIds))     $reasons[] = 'no image record';
                if (in_array($p->id, $missingFileIds))  $reasons[] = 'file missing';
                return [$p->id, Str::limit($p->name, 50), implode(', ', $reasons)];
            })
        );

        if (count($allIds) > 30) {
            $this->line("... and " . (count($allIds) - 30) . " more.");
        }

        // Dry run stops here
        if ($dryRun) {
            $this->newLine();
            $this->warn("Dry run complete. No products were deleted.");
            $this->line("Run with --dry-run=0 to delete.");
            return Command::SUCCESS;
        }

        // Confirmation
        $this->newLine();
        $confirm = $this->ask('Type DELETE to confirm permanent deletion');
        if ($confirm !== 'DELETE') {
            $this->warn('Aborted.');
            return Command::SUCCESS;
        }

        // Execute deletion
        $deleted = 0;
        DB::transaction(function () use ($allIds, &$deleted) {
            // Delete related images, cart items, wishlist entries, etc.
            ProductImage::whereIn('product_id', $allIds)->delete();
            DB::table('wishlists')->whereIn('product_id', $allIds)->delete();
            DB::table('cart_items')->whereIn('product_id', $allIds)->delete();
            DB::table('order_items')->whereIn('product_id', $allIds)->delete();
            DB::table('discounts')->whereIn('product_id', $allIds)->delete();

            $deleted = Product::whereIn('id', $allIds)->delete();
        });

        // Log
        $logData = [
            'timestamp'   => now()->toIso8601String(),
            'deleted_ids' => $allIds,
            'count'       => $deleted,
        ];
        $logPath = storage_path('logs/cleanup-products-' . now()->format('Y-m-d_His') . '.json');
        file_put_contents($logPath, json_encode($logData, JSON_PRETTY_PRINT));

        $this->newLine();
        $this->info("Deleted {$deleted} products.");
        $this->info("Log: {$logPath}");

        return Command::SUCCESS;
    }
}
