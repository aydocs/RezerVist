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
            if (Schema::hasColumn('users', 'tckn')) {
                $table->dropColumn('tckn');
            }
            if (Schema::hasColumn('users', 'identity_number')) {
                $table->dropColumn('identity_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tckn', 11)->nullable()->after('phone');
            $table->string('identity_number')->nullable()->after('tckn');
        });
    }
};
