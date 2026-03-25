<?php

namespace App\Services\Catalog;

use Illuminate\Database\Eloquent\Builder;

/**
 * Strategy Pattern — Strategy Interface.
 *
 * Defines a family of sorting algorithms for the product catalog.
 * Each concrete strategy encapsulates a specific sorting logic.
 */
interface SortingStrategy
{
    /**
     * Apply the sorting algorithm to the query builder.
     */
    public function apply(Builder $query): Builder;

    /**
     * Human-readable label for the UI dropdown.
     */
    public function getLabel(): string;

    /**
     * Machine-readable key used in URL parameters.
     */
    public function getKey(): string;
}
