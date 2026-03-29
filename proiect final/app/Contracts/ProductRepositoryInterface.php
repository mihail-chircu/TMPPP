<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

/**
 * Proxy Pattern — Subject Interface.
 *
 * Common interface for the real product repository
 * and its caching proxy, so they can be used interchangeably.
 */
interface ProductRepositoryInterface
{
    public function getFeatured(int $limit = 8): Collection;

    public function getNew(int $limit = 8): Collection;

    public function getSale(int $limit = 8): Collection;

    public function getPopular(int $limit = 8): Collection;
}
