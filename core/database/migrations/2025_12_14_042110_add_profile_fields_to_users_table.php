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
        Schema::table('users', function (Blueprint $table) {
            $table->string('tckn', 11)->nullable()->after('phone');
            $table->text('address')->nullable()->after('tckn');
            $table->string('city')->nullable()->after('address');
            $table->string('district')->nullable()->after('city');
            $table->string('zip_code', 10)->nullable()->after('district');
            $table->string('country')->default('Turkey')->after('zip_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['identity_number', 'address', 'city', 'country', 'zip_code']);
        });
    }
};
