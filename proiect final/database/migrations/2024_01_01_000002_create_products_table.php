<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('sku')->unique()->nullable();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('brand')->nullable();
            $table->unsignedTinyInteger('age_min')->default(0);
            $table->unsignedTinyInteger('age_max')->default(99);
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->enum('badge', ['none', 'new', 'hot'])->default('none');
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('sales_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->index('category_id');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('brand');
            $table->index('price');
            $table->index(['age_min', 'age_max']);
            $table->index('stock');
            $table->index('sales_count');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
