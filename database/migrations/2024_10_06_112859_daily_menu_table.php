<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_menus', function (Blueprint $table) {
            $table->id();
            $table->date('menu_date');
            $table->integer('soup')->nullable();
            $table->integer('bouillon')->nullable();
            $table->json('three_variant_meals')->nullable();
            $table->json('two_variant_meals')->nullable();
            $table->integer('meatless_meal')->nullable();
            $table->integer('cheap_meal')->nullable();
            $table->integer('expensive_meal')->nullable();
            $table->json('default_meals')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_menus'); // Drop the table if it exists
    }
};
