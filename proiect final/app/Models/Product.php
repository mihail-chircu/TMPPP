<?php

namespace App\Models;

use App\Contracts\Prototype;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model implements Prototype
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'currency',
        'sku',
        'source_url',
        'category_id',
        'brand',
        'age_min',
        'age_max',
        'stock',
        'is_active',
        'is_featured',
        'badge',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function activeDiscount(): HasOne
    {
        return $this->hasOne(Discount::class)
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')
            ->withTimestamps();
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOnSale(Builder $query): Builder
    {
        return $query->whereHas('activeDiscount');
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeByAge(Builder $query, int $age): Builder
    {
        return $query->where('age_min', '<=', $age)->where('age_max', '>=', $age);
    }

    public function scopeByPriceRange(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeByBrand(Builder $query, string $brand): Builder
    {
        return $query->where('brand', $brand);
    }

    public function getCurrentPriceAttribute(): float
    {
        if ($this->activeDiscount) {
            return (float) $this->activeDiscount->discounted_price;
        }

        return (float) $this->price;
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->activeDiscount !== null;
    }

    public function getDiscountPercentAttribute(): float
    {
        if ($this->activeDiscount) {
            return (float) $this->activeDiscount->discount_percent;
        }

        return 0;
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        $path = $this->primaryImage?->path;

        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return $path;
        }

        return null;
    }

    /**
     * Prototype Pattern: Create a duplicate of this product.
     *
     * Deep-clones the product with a new slug and SKU,
     * and copies all associated images.
     */
    public function duplicate(): self
    {
        $clone = $this->replicate([
            'slug',
            'sku',
            'views_count',
            'sales_count',
        ]);

        $clone->name = $this->name . ' (Copie)';
        $clone->slug = Str::slug($clone->name) . '-' . Str::random(5);
        $clone->sku = $this->sku ? $this->sku . '-COPY-' . strtoupper(Str::random(4)) : null;
        $clone->is_active = false;
        $clone->views_count = 0;
        $clone->sales_count = 0;
        $clone->save();

        // Deep copy: clone associated images
        foreach ($this->images as $image) {
            $clone->images()->create([
                'path' => $image->path,
                'alt' => $image->alt,
                'is_primary' => $image->is_primary,
                'sort_order' => $image->sort_order,
            ]);
        }

        return $clone;
    }
}
