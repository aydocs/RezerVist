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
        // 1. Businesses Table
        $this->convertToJson('businesses', ['name', 'description']);

        // 2. Posts Table
        $this->convertToJson('posts', ['title', 'content']);

        // 3. Post Categories
        $this->convertToJson('post_categories', ['name', 'description']);

        // 4. Main Categories (for businesses)
        $this->convertToJson('categories', ['name']);
    }

    /**
     * Helper to safely convert columns to JSON and migrate data
     */
    private function convertToJson(string $table, array $columns)
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                if (Schema::hasColumn($table->getTable(), $column)) {
                    $table->renameColumn($column, $column.'_old');
                }
            }
        });

        Schema::table($table, function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                $table->json($column)->nullable()->after($column.'_old');
            }
        });

        // Migrate data
        $records = DB::table($table)->get();
        foreach ($records as $record) {
            $updateData = [];
            foreach ($columns as $column) {
                $oldValue = $record->{$column.'_old'};
                $updateData[$column] = json_encode(['tr' => $oldValue], JSON_UNESCAPED_UNICODE);
            }
            DB::table($table)->where('id', $record->id)->update($updateData);
        }

        Schema::table($table, function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                $table->dropColumn($column.'_old');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting JSON to string is complex and data-loss prone.
        // For down, we just revert back to string with 'tr' value.
        $this->revertToString('businesses', ['name' => 'string', 'description' => 'text']);
        $this->revertToString('posts', ['title' => 'string', 'content' => 'longText']);
        $this->revertToString('post_categories', ['name' => 'string', 'description' => 'text']);
        $this->revertToString('categories', ['name' => 'string']);
    }

    private function revertToString(string $table, array $columns)
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column => $type) {
            Schema::table($table, function (Blueprint $table) use ($column, $type) {
                $table->{$type}($column.'_new')->nullable()->after($column);
            });

            $records = DB::table($table)->get();
            foreach ($records as $record) {
                $translations = json_decode($record->{$column} ?? '{}', true);
                DB::table($table)->where('id', $record->id)->update([
                    $column.'_new' => $translations['tr'] ?? ($translations['en'] ?? ''),
                ]);
            }

            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
                $table->renameColumn($column.'_new', $column);
            });
        }
    }
};
