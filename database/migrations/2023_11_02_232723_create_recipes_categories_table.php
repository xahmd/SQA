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
        Schema::create('recipes_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId("recipe_id");
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete("cascade");
            $table->foreignId("category_id");
            $table->foreign('category_id')->references('id')->on('categories')->onDelete("cascade");
            $table->unique(['recipe_id', 'category_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes_categories');
    }
};
