<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('order_items', 'status')) {
                $table->enum('status', ['pending', 'preparing', 'ready', 'completed'])->default('pending')->after('notes');
            }
            if (! Schema::hasColumn('order_items', 'station')) {
                $table->string('station')->nullable()->after('status')->comment('kitchen, bar, cold, etc.');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['status', 'station']);
        });
    }
};
