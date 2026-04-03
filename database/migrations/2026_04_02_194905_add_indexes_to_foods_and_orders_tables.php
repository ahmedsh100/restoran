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
        Schema::table('foods', function (Blueprint $table) {
            $table->index('category');
            $table->index('is_available');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
            $table->index('payment_status');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('food_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('food_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['is_available']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_status']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['food_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['food_id']);
        });
    }
};
