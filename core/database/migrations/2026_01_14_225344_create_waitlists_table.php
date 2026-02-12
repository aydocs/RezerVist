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
        Schema::create('waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->date('reservation_date');
            $table->string('preferred_time_range')->nullable(); // e.g. "18:00-20:00"
            $table->integer('guest_count')->default(2);
            $table->enum('status', ['waiting', 'notified', 'expired', 'converted'])->default('waiting');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'reservation_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waitlists');
    }
};
