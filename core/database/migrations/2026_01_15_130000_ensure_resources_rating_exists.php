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
        if (!Schema::hasColumn('staff', 'rating')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->decimal('rating', 3, 2)->default(0)->after('is_active');
                $table->integer('rating_count')->default(0)->after('rating');
            });
        }

        if (!Schema::hasColumn('resources', 'rating')) {
            Schema::table('resources', function (Blueprint $table) {
                $table->decimal('rating', 3, 2)->default(0)->after('is_available');
                $table->integer('rating_count')->default(0)->after('rating');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down action needed for a fixer migration to avoid accidental data loss
    }
};
