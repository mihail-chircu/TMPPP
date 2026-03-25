<?php

namespace App\Services\Catalog\Strategies;

use App\Services\Catalog\SortingStrategy;
use Illuminate\Database\Eloquent\Builder;

class NewestStrategy implements SortingStrategy
{
    public function apply(Builder $query): Builder
    {
        return $query->latest();
    }

    public function getLabel(): string
    {
        return 'Cele mai noi';
    }

    public function getKey(): string
    {
        return 'newest';
    }
}
