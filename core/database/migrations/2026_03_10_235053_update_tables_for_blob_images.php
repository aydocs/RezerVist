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
        Schema::table('businesses', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->after('logo', function($table) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE businesses ADD COLUMN image_blob LONGBLOB NULL AFTER logo');
            });
        });

        Schema::table('business_images', function (Illuminate\Database\Schema\Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE business_images ADD COLUMN image_blob LONGBLOB NULL AFTER image_path');
        });

        Schema::table('menus', function (Illuminate\Database\Schema\Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE menus ADD COLUMN image_blob LONGBLOB NULL AFTER image');
        });

        Schema::table('users', function (Illuminate\Database\Schema\Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE users ADD COLUMN profile_photo_blob LONGBLOB NULL AFTER profile_photo_path');
        });
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
