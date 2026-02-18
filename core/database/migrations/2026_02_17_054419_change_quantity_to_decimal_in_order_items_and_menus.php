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
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('quantity', 12, 3)->default(1)->change();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->decimal('stock_quantity', 12, 3)->nullable()->change();
            $table->decimal('low_stock_alert', 12, 3)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->change();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->integer('stock_quantity')->nullable()->change();
            $table->integer('low_stock_alert')->nullable()->change();
        });
    }
};
