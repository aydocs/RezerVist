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
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('iyzico_submerchant_key')->nullable()->after('commission_rate');
            $table->string('submerchant_type')->nullable()->comment('PERSONAL, PRIVATE_COMPANY, LIMITED_OR_JOINT_STOCK_COMPANY');
            $table->string('tax_office')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('legal_company_name')->nullable();
            $table->string('iyzico_iban')->nullable(); // Using a specific name to avoid conflict with vendor withdrawal IBAN if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            //
        });
    }
};
