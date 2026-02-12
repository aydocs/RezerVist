<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('happy_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "Happy Hour İçecekler"
            $table->enum('type', ['percentage', 'fixed_amount', 'bogo'])->default('percentage'); // Buy One Get One
            $table->decimal('discount_value', 5, 2)->nullable(); // 20.00 for 20% or 10.00 TL
            $table->time('start_time'); // 17:00
            $table->time('end_time');   // 19:00
            $table->json('days_of_week'); // ["monday", "tuesday", ...]
            $table->json('applicable_categories')->nullable(); // ["DRINKS", "STARTERS"]
            $table->json('applicable_items')->nullable(); // Specific menu_ids
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('happy_hours');
    }
};
