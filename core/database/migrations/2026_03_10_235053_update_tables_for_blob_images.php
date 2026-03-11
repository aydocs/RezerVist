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
        // Add LONGBLOB columns using raw SQL since standard binary() might not be enough for large images
        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE businesses ADD COLUMN image_blob LONGBLOB NULL AFTER logo');
        } catch (\Exception $e) {}

        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE business_images ADD COLUMN image_blob LONGBLOB NULL AFTER image_path');
        } catch (\Exception $e) {}

        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE menus ADD COLUMN image_blob LONGBLOB NULL AFTER image');
        } catch (\Exception $e) {}

        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE users ADD COLUMN profile_photo_blob LONGBLOB NULL AFTER profile_photo_path');
        } catch (\Exception $e) {}
    }

    public function down(): void
    {
        Schema::table('businesses', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('image_blob');
        });
        Schema::table('business_images', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('image_blob');
        });
        Schema::table('menus', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('image_blob');
        });
        Schema::table('users', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('profile_photo_blob');
        });
    }
};
