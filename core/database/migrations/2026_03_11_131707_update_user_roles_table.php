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
        // Update all users with role 'customer' to 'user'
        \App\Models\User::where('role', 'customer')->update(['role' => 'user']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert all users with role 'user' back to 'customer'
        \App\Models\User::where('role', 'user')->update(['role' => 'customer']);
    }
};
