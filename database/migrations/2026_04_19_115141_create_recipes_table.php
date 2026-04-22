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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id('recipeID');
            $table->foreignId('productID')->constrained(table: 'products', column: 'productID')->cascadeOnDelete();
            $table->foreignId('ingredientID')->constrained(table: 'ingredients', column: 'ingredientID')->cascadeOnDelete();
            $table->integer('qtyUsed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
