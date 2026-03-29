<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

/**
 * Composite Pattern — Component Interface.
 *
 * Declares the common interface for both leaf categories
 * (no children) and composite categories (with children),
 * allowing uniform treatment of the category tree.
 */
interface CategoryComponent
{
    public function getName(): string;

    public function getSlug(): string;

    /**
     * Total products in this node and all descendants.
     */
    public function getProductCount(): int;

    /**
     * Whether this is a leaf node (no children).
     */
    public function isLeaf(): bool;

    /**
     * Get child components.
     */
    public function getChildComponents(): Collection;
}
