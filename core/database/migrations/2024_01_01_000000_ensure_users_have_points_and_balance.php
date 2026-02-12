<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'points')) {
                $table->integer('points')->default(0)->after('email');
            }
            if (!Schema::hasColumn('users', 'balance')) {
                $table->decimal('balance', 10, 2)->default(0)->after('points');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['points', 'balance']);
        });
    }
};
