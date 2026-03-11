<?php

namespace App\Models;

use App\Contracts\CategoryComponent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Composite Pattern — Composite / Leaf node.
 *
 * A Category can be either a leaf (no children) or a composite
 * (contains child categories). Both are treated uniformly
 * through the CategoryComponent interface.
 */
class Category extends Model implements CategoryComponent
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'source_url',
        'description',
        'image',
        'parent_id',
        'sort_order',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function activeChildren(): HasMany
    {
        return $this->children()->where('is_active', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function getProductCountAttribute(): int
    {
        return $this->getProductCount();
    }

    // ── Composite Pattern implementation ─────────────────────

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Recursively counts products in this category and all descendants.
     */
    public function getProductCount(): int
    {
        $count = $this->products()->count();

        foreach ($this->children as $child) {
            $count += $child->getProductCount();
        }

        return $count;
    }

    public function isLeaf(): bool
    {
        return $this->children()->count() === 0;
    }

    public function getChildComponents(): Collection
    {
        return $this->activeChildren;
    }
}
