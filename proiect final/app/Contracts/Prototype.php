<?php

namespace App\Contracts;

/**
 * Prototype Pattern Interface
 *
 * Allows objects to be cloned without coupling to their specific classes.
 */
interface Prototype
{
    /**
     * Create a deep copy of the current object.
     */
    public function duplicate(): self;
}
