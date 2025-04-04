<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('municipality')->nullable();
            $table->string('postal_code');
            $table->string('country')->default('Северна Македонија');
            $table->text('additional_description')->nullable();

            $table->enum('payment_method', ['cash', 'card'])->default('cash');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');

            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('delivery_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
