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
        Schema::table('orders', function (Blueprint $table) {
            // Compound index for finding active orders by resource (POS)
            $table->index(['business_id', 'resource_id', 'status'], 'idx_pos_active_orders');
            
            // Index for finding active orders by business (Kitchen/Dashboard)
            $table->index(['business_id', 'status'], 'idx_kitchen_active_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_pos_active_orders');
            $table->dropIndex('idx_kitchen_active_orders');
        });
    }
};
