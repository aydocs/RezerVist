<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('TRY');
            $table->string('status')->default('pending'); // pending, paid, cancelled
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('billing_name')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('tax_office')->nullable();
            $table->string('tax_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
