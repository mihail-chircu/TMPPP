<?php

namespace App\Services\Catalog\Strategies;

use App\Services\Catalog\SortingStrategy;
use Illuminate\Database\Eloquent\Builder;

class PriceAscStrategy implements SortingStrategy
{
    public function apply(Builder $query): Builder
    {
        return $query->orderBy('price', 'asc');
    }

    public function getLabel(): string
    {
        return 'Preț: mic → mare';
    }

    public function getKey(): string
    {
        return 'price_asc';
    }
}
