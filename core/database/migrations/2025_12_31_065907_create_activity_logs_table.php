<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action_type'); // login, logout, failed_login, user_created, user_updated, etc.
            $table->text('description'); // Turkish description
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Extra data like old/new values
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('action_type');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
