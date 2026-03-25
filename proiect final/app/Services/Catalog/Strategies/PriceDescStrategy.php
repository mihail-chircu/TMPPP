<?php

namespace App\Services\Catalog\Strategies;

use App\Services\Catalog\SortingStrategy;
use Illuminate\Database\Eloquent\Builder;

class PriceDescStrategy implements SortingStrategy
{
    public function apply(Builder $query): Builder
    {
        return $query->orderBy('price', 'desc');
    }

    public function getLabel(): string
    {
        return 'Preț: mare → mic';
    }

    public function getKey(): string
    {
        return 'price_desc';
    }
}
