<?php

namespace App\Services\Catalog;

use App\Services\Catalog\Strategies\NewestStrategy;
use App\Services\Catalog\Strategies\PopularityStrategy;
use App\Services\Catalog\Strategies\PriceAscStrategy;
use App\Services\Catalog\Strategies\PriceDescStrategy;
use Illuminate\Database\Eloquent\Builder;

/**
 * Strategy Pattern — Context.
 *
 * Manages sorting strategies and applies the selected one
 * to a product query. The client (CatalogController) selects
 * the strategy at runtime via a URL parameter.
 */
class ProductSorter
{
    /** @var array<string, SortingStrategy> */
    private array $strategies = [];

    private string $defaultKey = 'newest';

    public function __construct()
    {
        $this->register(new NewestStrategy());
        $this->register(new PriceAscStrategy());
        $this->register(new PriceDescStrategy());
        $this->register(new PopularityStrategy());
    }

    public function register(SortingStrategy $strategy): void
    {
        $this->strategies[$strategy->getKey()] = $strategy;
    }

    /**
     * Apply the selected sorting strategy to the query.
     */
    public function apply(Builder $query, ?string $key): Builder
    {
        $strategy = $this->strategies[$key] ?? $this->strategies[$this->defaultKey];

        return $strategy->apply($query);
    }

    /**
     * Get all available strategies for the view dropdown.
     */
    public function getAvailableStrategies(): array
    {
        return array_map(fn (SortingStrategy $s) => [
            'key' => $s->getKey(),
            'label' => $s->getLabel(),
        ], $this->strategies);
    }
}
