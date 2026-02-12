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
        // Schema::table('businesses', function (Blueprint $table) {
        //     $table->decimal('commission_rate', 10, 2)->default(5.00)->change();
        // });

        // Update existing businesses
        \Illuminate\Support\Facades\DB::table('businesses')
            ->update(['commission_rate' => 5.00]);
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
