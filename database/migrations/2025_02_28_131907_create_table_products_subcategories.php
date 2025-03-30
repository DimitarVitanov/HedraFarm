<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('subcategory_id')->constrained('product_subcategories');
            $table->softDeletes();
        });
    }


    public function down(): void
    {
        Schema::table('products_subcategories', function (Blueprint $table) {
            $table->dropColumn(['product_id'. 'subcategory_id']);
            });
    }
};
