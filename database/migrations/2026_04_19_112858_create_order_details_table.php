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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('detailID');
            $table->foreignId('orderID')->constrained(table: 'orders', column: 'orderID')->cascadeOnDelete();
            $table->foreignId('productID')->constrained(table: 'products', column: 'productID')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('unitPrice', 15, 2);
            $table->decimal('subTotal', 15, 2);
            $table->decimal('ingredientCost', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
