<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Proxy Pattern — Real Subject.
 *
 * Performs actual database queries to fetch products.
 * Wrapped by CachingProductProxy for performance.
 */
class ProductRepository implements ProductRepositoryInterface
{
    public function getFeatured(int $limit = 8): Collection
    {
        return Product::with(['primaryImage', 'activeDiscount', 'category'])
            ->active()
            ->featured()
            ->inStock()
            ->limit($limit)
            ->get();
    }

    public function getNew(int $limit = 8): Collection
    {
        return Product::with(['primaryImage', 'activeDiscount', 'category'])
            ->active()
            ->inStock()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getSale(int $limit = 8): Collection
    {
        return Product::with(['primaryImage', 'activeDiscount', 'category'])
            ->active()
            ->onSale()
            ->inStock()
            ->limit($limit)
            ->get();
    }

    public function getPopular(int $limit = 8): Collection
    {
        return Product::with(['primaryImage', 'activeDiscount', 'category'])
            ->active()
            ->inStock()
            ->orderBy('sales_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
