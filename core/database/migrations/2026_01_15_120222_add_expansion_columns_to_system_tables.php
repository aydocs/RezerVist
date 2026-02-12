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
        // 1. Happy Hour support for business hours
        Schema::table('business_hours', function (Blueprint $table) {
            if (! Schema::hasColumn('business_hours', 'discount_percent')) {
                $table->integer('discount_percent')->nullable()->after('is_closed');
            }
        });

        // 2. Inventory tracking for resources
        Schema::table('resources', function (Blueprint $table) {
            if (! Schema::hasColumn('resources', 'stock')) {
                $table->integer('stock')->default(0)->after('capacity');
            }
            if (! Schema::hasColumn('resources', 'requires_inventory')) {
                $table->boolean('requires_inventory')->default(false)->after('stock');
            }
        });

        // 3. User activity tracking for CRM
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'last_reservation_at')) {
                $table->timestamp('last_reservation_at')->nullable()->after('points');
            }
        });

        // 4. Reservation loyalty point usage
        Schema::table('reservations', function (Blueprint $table) {
            if (! Schema::hasColumn('reservations', 'points_spent')) {
                $table->integer('points_spent')->default(0)->after('total_amount');
            }
            if (! Schema::hasColumn('reservations', 'loyalty_discount')) {
                $table->decimal('loyalty_discount', 10, 2)->default(0)->after('points_spent');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_hours', function (Blueprint $table) {
            $table->dropColumn('discount_percent');
        });
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn(['stock', 'requires_inventory']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_reservation_at');
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['points_spent', 'loyalty_discount']);
        });
    }
};
