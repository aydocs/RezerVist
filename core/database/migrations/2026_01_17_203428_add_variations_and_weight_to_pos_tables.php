<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'options')) {
                $table->json('options')->nullable()->after('price');
            }
            if (!Schema::hasColumn('menus', 'unit_type')) {
                $table->enum('unit_type', ['piece', 'weight'])->default('piece')->after('options');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'selected_options')) {
                $table->json('selected_options')->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('order_items', 'weight_grams')) {
                $table->integer('weight_grams')->nullable()->after('selected_options');
            }
        });

        // Update existing businesses that have null or empty master_pin first
        DB::table('businesses')->whereNull('master_pin')->orWhere('master_pin', '')->update(['master_pin' => '00000000']);

        // Set default Master PIN for future businesses
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('master_pin', 8)->default('00000000')->change();
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['options', 'unit_type']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['selected_options', 'weight_grams']);
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->string('master_pin', 8)->default(null)->change();
        });
    }
};
