<?php

namespace App\Console\Commands;

use App\Models\Discount;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessDiscounts extends Command
{
    protected $signature = 'discounts:process';
    protected $description = 'Activate and deactivate time-based discounts';

    public function handle(): int
    {
        $now = Carbon::now();

        // Activate discounts that should start
        $activated = Discount::where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now)
            ->count();

        // Deactivate expired discounts
        $deactivated = Discount::where('is_active', true)
            ->where('ends_at', '<', $now)
            ->update(['is_active' => false]);

        $this->info("Discounts processed: {$activated} active, {$deactivated} deactivated.");

        return Command::SUCCESS;
    }
}
