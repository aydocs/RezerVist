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
            $table->boolean('is_in_moment')->default(false)->after('comment');
            $table->text('business_response')->nullable()->after('is_in_moment');
            $table->timestamp('responded_at')->nullable()->after('business_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['is_in_moment', 'business_response', 'responded_at']);
        });
    }
};
