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
        Schema::create('business_applications', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->string('address');
            $table->string('phone');
            $table->string('email');

            // Legal
            $table->string('trade_registry_no');
            $table->string('tax_id');

            // Documents (Paths)
            $table->string('trade_registry_document');
            $table->string('tax_document');
            $table->string('license_document');
            $table->string('id_document');
            $table->string('bank_document');

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_applications');
    }
};
