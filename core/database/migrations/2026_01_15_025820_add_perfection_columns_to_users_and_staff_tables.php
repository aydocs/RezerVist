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
        // Logic handled by 2026_01_15_130000_ensure_resources_rating_exists.php due to previous conflicts
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'points')) {
                $table->dropColumn('points');
            }
        });

        Schema::table('staff', function (Blueprint $table) {
            if (Schema::hasColumn('staff', 'rating')) {
                $table->dropColumn(['rating', 'rating_count']);
            }
        });

        Schema::table('resources', function (Blueprint $table) {
            if (Schema::hasColumn('resources', 'rating')) {
                $table->dropColumn(['rating', 'rating_count']);
            }
        });

        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'staff_id')) {
                $table->dropForeign(['staff_id']);
                $table->dropColumn('staff_id');
            }
            if (Schema::hasColumn('reviews', 'resource_id')) {
                $table->dropForeign(['resource_id']);
                $table->dropColumn('resource_id');
            }
        });
    }
};
