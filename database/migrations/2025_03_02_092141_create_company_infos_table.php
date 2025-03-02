<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('worktime')->nullable();
            $table->string('quote')->nullable();
            $table->string('instagram_acc_link')->nullable();
            $table->string('facebook_acc_link')->nullable();
            $table->string('linkedin_acc_link')->nullable();
            $table->boolean('show_instagram')->nullable()->default(false);
            $table->boolean('show_facebook')->nullable()->default(false);
            $table->boolean('show_linkedin')->nullable()->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_infos');
    }
};
