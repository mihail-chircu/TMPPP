<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Proxy Pattern — Proxy.
 *
 * Controls access to the real ProductRepository by adding
 * a caching layer. Expensive database queries are cached
 * and served from memory on subsequent requests.
 *
 * The proxy implements the same interface as the real subject,
 * so the client (HomeController) doesn't know it's using a proxy.
 */
class CachingProductProxy implements ProductRepositoryInterface
{
    private const TTL = 1800; // 30 minutes

    public function __construct(
        private ProductRepository $repository,
    ) {}

    public function getFeatured(int $limit = 8): Collection
    {
        return Cache::remember(
            "products.featured.{$limit}",
            self::TTL,
            fn () => $this->repository->getFeatured($limit),
        );
    }

    public function getNew(int $limit = 8): Collection
    {
        return Cache::remember(
            "products.new.{$limit}",
            self::TTL,
            fn () => $this->repository->getNew($limit),
        );
    }

    public function getSale(int $limit = 8): Collection
    {
        return Cache::remember(
            "products.sale.{$limit}",
            self::TTL,
            fn () => $this->repository->getSale($limit),
        );
    }

    public function getPopular(int $limit = 8): Collection
    {
        return Cache::remember(
            "products.popular.{$limit}",
            self::TTL,
            fn () => $this->repository->getPopular($limit),
        );
    }
}
