<?php

namespace App\Services\Catalog\Strategies;

use App\Services\Catalog\SortingStrategy;
use Illuminate\Database\Eloquent\Builder;

class PopularityStrategy implements SortingStrategy
{
    public function apply(Builder $query): Builder
    {
        return $query->orderBy('sales_count', 'desc');
    }

    public function getLabel(): string
    {
        return 'Popularitate';
    }

    public function getKey(): string
    {
        return 'popular';
    }
}
