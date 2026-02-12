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
        Schema::table('reservations', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('status');
            $table->string('recurrence_pattern')->nullable()->after('is_recurring'); // daily, weekly, monthly
            $table->date('recurrence_end_date')->nullable()->after('recurrence_pattern');
            $table->foreignId('parent_reservation_id')->nullable()->after('recurrence_end_date')->constrained('reservations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['parent_reservation_id']);
            $table->dropColumn(['is_recurring', 'recurrence_pattern', 'recurrence_end_date', 'parent_reservation_id']);
        });
    }
};
