<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminResetSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@Rezervist.com';

        // Force delete if exists to start fresh (optional, but safer to update)
        // User::where('email', $email)->delete();

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user verified/created with ID: '.$user->id);
    }
}
