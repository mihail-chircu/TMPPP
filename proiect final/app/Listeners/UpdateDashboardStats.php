<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Cache;

/**
 * Observer Pattern — Concrete Observer.
 *
 * Reacts to OrderPlaced by invalidating cached dashboard
 * statistics so they are recalculated on next admin visit.
 */
class UpdateDashboardStats
{
    public function handle(OrderPlaced $event): void
    {
        Cache::forget('dashboard.total_orders');
        Cache::forget('dashboard.total_revenue');
        Cache::forget('dashboard.recent_orders');
    }
}
