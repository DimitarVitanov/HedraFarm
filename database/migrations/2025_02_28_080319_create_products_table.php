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
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('quantity');
            $table->foreignId('product_category_id')->constrained();
            $table->string('main_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('show_trending')->default(false);
            $table->boolean('show_on_sale')->default(false);
            $table->boolean('show_best_seller')->default(false);
            $table->boolean('show_top_rated')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
