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
        Schema::create('recipes_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId("recipe_id");
            $table->foreign('recipe_id')->references('id')->on('recipes');
            $table->foreignId("ingredient_id");
            $table->foreign('ingredient_id')->references('id')->on('ingredients');
            $table->unique(['recipe_id', 'ingredient_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes_ingredients');
    }
};
