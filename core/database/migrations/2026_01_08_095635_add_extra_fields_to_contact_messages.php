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
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('priority')->default('normal')->after('subject'); // low, normal, high, urgent
            $table->text('admin_notes')->nullable()->after('reply');
            $table->string('status')->default('open')->after('is_read'); // open, replied, closed
            $table->timestamp('closed_at')->nullable()->after('replied_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['priority', 'admin_notes', 'status', 'closed_at']);
        });
    }
};
