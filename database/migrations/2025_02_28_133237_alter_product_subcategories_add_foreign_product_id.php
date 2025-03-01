<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_subcategories', function (Blueprint $table) {
               $table->foreignId('product_category_id')->after('name')->constrained('product_categories');
        });
    }

    public function down(): void
    {
        Schema::table('product_subcategories', function (Blueprint $table) {
            $table->dropForeign(['product_category_id']);
            $table->dropColumn('product_category_id');
        });
    }
};
