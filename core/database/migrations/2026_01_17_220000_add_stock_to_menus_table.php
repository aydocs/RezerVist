<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'stock_enabled')) {
                $table->boolean('stock_enabled')->default(false)->after('is_available');
            }
            if (!Schema::hasColumn('menus', 'stock_quantity')) {
                $table->integer('stock_quantity')->nullable()->after('stock_enabled');
            }
            if (!Schema::hasColumn('menus', 'low_stock_alert')) {
                $table->integer('low_stock_alert')->nullable()->after('stock_quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['stock_enabled', 'stock_quantity', 'low_stock_alert']);
        });
    }
};
