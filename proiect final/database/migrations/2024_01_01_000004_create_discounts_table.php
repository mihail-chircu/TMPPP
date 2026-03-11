<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('discount_percent', 5, 2);
            $table->decimal('original_price', 10, 2);
            $table->decimal('discounted_price', 10, 2);
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index(['starts_at', 'ends_at']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
