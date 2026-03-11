<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('source_url')->nullable()->unique()->after('slug');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('source_url')->nullable()->unique()->after('sku');
            $table->string('currency', 8)->default('MDL')->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('source_url');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['source_url', 'currency']);
        });
    }
};
