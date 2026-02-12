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
        Schema::create('business_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week'); // 0 = Pazar, 1 = Pazartesi, ..., 6 = Cumartesi
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_closed')->default(false); // Kapalı mı?
            $table->date('special_date')->nullable(); // Özel günler için (tatil, özel saat vb.)
            $table->string('note')->nullable(); // "Yılbaşı kapalı" gibi notlar
            $table->timestamps();

            // Bir işletme için aynı gün aynı tarihte birden fazla kayıt olmasın
            $table->unique(['business_id', 'day_of_week', 'special_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_hours');
    }
};
