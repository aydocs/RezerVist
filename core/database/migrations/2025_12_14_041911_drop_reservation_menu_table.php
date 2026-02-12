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
        Schema::dropIfExists('reservation_menu');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('reservation_menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }
};
