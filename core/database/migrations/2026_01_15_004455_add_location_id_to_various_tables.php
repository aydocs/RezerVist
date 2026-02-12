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
            if (! Schema::hasColumn('reservations', 'location_id')) {
                $table->foreignId('location_id')->nullable()->after('business_id')->constrained()->onDelete('set null');
            }
        });

        Schema::table('resources', function (Blueprint $table) {
            if (! Schema::hasColumn('resources', 'location_id')) {
                $table->foreignId('location_id')->nullable()->after('business_id')->constrained()->onDelete('set null');
            }
        });

        Schema::table('staff', function (Blueprint $table) {
            if (! Schema::hasColumn('staff', 'location_id')) {
                $table->foreignId('location_id')->nullable()->after('business_id')->constrained()->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropConstrainedForeignId('location_id');
        });
        Schema::table('resources', function (Blueprint $table) {
            $table->dropConstrainedForeignId('location_id');
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('location_id');
        });
    }
};
