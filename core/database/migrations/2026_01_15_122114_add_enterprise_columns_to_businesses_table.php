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
        Schema::table('businesses', function (Blueprint $table) {
            $table->integer('surge_threshold')->nullable()->default(80); // % occupancy
            $table->decimal('surge_multiplier', 5, 2)->nullable()->default(1.50);
            $table->string('custom_domain')->nullable()->unique();
            $table->boolean('waiter_kiosk_enabled')->default(false);
            $table->json('kiosk_config')->nullable();
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->decimal('surge_price', 10, 2)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['surge_threshold', 'surge_multiplier', 'custom_domain', 'waiter_kiosk_enabled', 'kiosk_config']);
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('surge_price');
        });
    }
};
