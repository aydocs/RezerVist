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
            $table->integer('occupancy_rate')->default(0)->after('waiter_kiosk_enabled'); // 0-100 doluluk yüzdesi
            $table->json('reservation_time_slots')->nullable()->after('occupancy_rate'); // Rezervasyon saat aralıkları
            $table->string('pos_api_token', 64)->nullable()->unique()->after('reservation_time_slots'); // POS entegrasyon token
            $table->timestamp('last_occupancy_update')->nullable()->after('pos_api_token'); // Son doluluk güncellemesi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['occupancy_rate', 'reservation_time_slots', 'pos_api_token', 'last_occupancy_update']);
        });
    }
};
