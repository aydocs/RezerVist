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
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('discount_amount', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('coupon_id');
            $table->dropColumn('discount_amount');
        });
    }
};
