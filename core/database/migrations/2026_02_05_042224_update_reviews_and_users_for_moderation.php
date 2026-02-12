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
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_reported')->default(false)->after('status');
            $table->timestamp('reported_at')->nullable()->after('is_reported');
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_review_blocked')->default(false)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['is_reported', 'reported_at']);
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_review_blocked');
        });
    }
};
