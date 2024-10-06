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
            $table->integer('type'); //Soup, main meal
            $table->integer('size'); //250 Grams OR 0.4l based on type
            $table->json('allergens')->nullable(); //1, 2, 3
            $table->integer('size-variant'); //M, XL, 2XL
            $table->integer('price');
            $table->integer('frequency')->default(0);
            $table->timestamps();
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
