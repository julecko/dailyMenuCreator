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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); //Hranolky, ...
            $table->integer('type'); //1 => Soup, 2 => Main Dish, 3 => Chicken 4=> Beef
            $table->string('size'); //250 Grams OR 0.4l based on type
            $table->json('allergens')->nullable(); //1, 2, 3
            $table->string('size_variant')->default('A'); //M, XL, 2XL
            $table->float('price')->nullable();
            $table->string('food_type')->nullable();
            $table->integer('frequency')->default(0);
            $table->date('last_used')->default('1970-01-01');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
