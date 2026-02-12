<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Businesses table - revert JSON to string
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('name_temp')->nullable();
            $table->text('description_temp')->nullable();
        });

        // Extract Turkish values from JSON
        DB::table('businesses')->get()->each(function ($business) {
            $name = json_decode($business->name, true);
            $description = json_decode($business->description, true);

            DB::table('businesses')->where('id', $business->id)->update([
                'name_temp' => $name['tr'] ?? $name ?? '',
                'description_temp' => $description['tr'] ?? $description ?? '',
            ]);
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['name', 'description']);
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->renameColumn('name_temp', 'name');
            $table->renameColumn('description_temp', 'description');
        });

        // Posts table - revert JSON to string
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title_temp')->nullable();
            $table->text('content_temp')->nullable();
        });

        DB::table('posts')->get()->each(function ($post) {
            $title = json_decode($post->title, true);
            $content = json_decode($post->content, true);

            DB::table('posts')->where('id', $post->id)->update([
                'title_temp' => $title['tr'] ?? $title ?? '',
                'content_temp' => $content['tr'] ?? $content ?? '',
            ]);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['title', 'content']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('title_temp', 'title');
            $table->renameColumn('content_temp', 'content');
        });

        // Categories table - revert JSON to string
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_temp')->nullable();
        });

        DB::table('categories')->get()->each(function ($category) {
            $name = json_decode($category->name, true);

            DB::table('categories')->where('id', $category->id)->update([
                'name_temp' => $name['tr'] ?? $name ?? '',
            ]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('name_temp', 'name');
        });

        // Post Categories table - revert JSON to string
        Schema::table('post_categories', function (Blueprint $table) {
            $table->string('name_temp')->nullable();
            $table->text('description_temp')->nullable();
        });

        DB::table('post_categories')->get()->each(function ($category) {
            $name = json_decode($category->name, true);
            $description = json_decode($category->description, true);

            DB::table('post_categories')->where('id', $category->id)->update([
                'name_temp' => $name['tr'] ?? $name ?? '',
                'description_temp' => $description['tr'] ?? $description ?? '',
            ]);
        });

        Schema::table('post_categories', function (Blueprint $table) {
            $table->dropColumn(['name', 'description']);
        });

        Schema::table('post_categories', function (Blueprint $table) {
            $table->renameColumn('name_temp', 'name');
            $table->renameColumn('description_temp', 'description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This would convert back to JSON, but we're removing the system entirely
        // so down() is intentionally left empty
    }
};
