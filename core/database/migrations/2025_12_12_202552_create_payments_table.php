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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->string('payment_id')->nullable(); // Iyzico payment id
            $table->string('conversation_id')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('paid_price', 10, 2);
            $table->string('status')->default('pending'); // pending, success, failed
            $table->string('currency')->default('TRY');
            $table->string('basket_id')->nullable();
            $table->string('card_family')->nullable();
            $table->string('card_type')->nullable();
            $table->string('bin_number')->nullable();
            $table->json('raw_result')->nullable(); // Store full response for debugging
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
