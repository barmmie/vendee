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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productId')
                ->constrained('products')
                ->cascadeOnDelete();
            $table->foreignId('userId')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->integer('quantity');
            $table->integer('purchaseCost');
            $table->integer('amountSpent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
