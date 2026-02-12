<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Staff;
use App\Models\Business;

class OwnerStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Find the user
        $user = User::where('email', 'owner1@test.com')->first();

        if (!$user) {
            $this->command->error("User owner1@test.com not found!");
            return;
        }

        // 2. Find the business (assuming user has one, or user IS the business owner)
        // Adjust logic based on your relationship. Usually $user->businesses or similar.
        // For now, let's assume the first business of this user or simply create one if needed, 
        // but likely it exists if they are an 'owner'.
        $business = Business::where('owner_id', $user->id)->first();

        if (!$business) {
             $this->command->error("Business for owner1@test.com not found!");
             return;
        }

        $this->command->info("Creating staff for business: " . $business->name);

        // 3. Define staff to create
        $staffMembers = [
            [
                'name' => 'Ahmet Yılmaz (Garson)',
                'role' => 'waiter',
                'pin_code' => '1111',
                'permissions' => ['take_order', 'view_tables', 'move_table', 'print_bill']
            ],
            [
                'name' => 'Mehmet Usta (Mutfak)',
                'role' => 'kitchen',
                'pin_code' => '2222',
                'permissions' => ['view_kds', 'update_order_status']
            ],
            [
                'name' => 'Ayşe Demir (Kasa/Müdür)',
                'role' => 'manager',
                'pin_code' => '3333',
                'permissions' => ['take_order', 'view_tables', 'process_payment', 'view_reports', 'manage_menu', 'void_order', 'apply_discount']
            ]
        ];

        foreach ($staffMembers as $staffData) {
            $staff = Staff::updateOrCreate(
                [
                    'business_id' => $business->id,
                    'pin_code' => $staffData['pin_code'] 
                ],
                [
                    'name' => $staffData['name'],
                    'position' => $staffData['role'],
                    'permissions' => $staffData['permissions'],
                    'is_active' => true
                ]
            );
            
            $this->command->info("Staff created/updated: {$staff->name} (PIN: {$staff->pin_code})");
        }
    }
}
