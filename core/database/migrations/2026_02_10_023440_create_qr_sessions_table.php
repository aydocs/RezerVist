<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_token', 64)->unique();
            $table->enum('status', ['active', 'ordering', 'paying', 'completed', 'expired'])->default('active');
            $table->decimal('total_paid', 10, 2)->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'resource_id', 'status']);
            $table->index('session_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_sessions');
    }
};
