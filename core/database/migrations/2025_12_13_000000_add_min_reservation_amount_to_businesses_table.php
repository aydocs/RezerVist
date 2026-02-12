<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->decimal('min_reservation_amount', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('min_reservation_amount');
        });
    }
};
