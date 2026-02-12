<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Business;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Optimized: Prepare massive array for insert
        // Use a smaller chunk size to avoid memory issues, but insert in batches
        Business::doesntHave('menus')->chunk(50, function ($businesses) {
            $menus = [];
            $now = now();
            
            foreach ($businesses as $business) {
                // Generate 5-12 menus per business
                // Since we can't use factory make() easily with relationships inside structure,
                // we'll manually use the logic from Factory or try make()->toArray()
                
                $factoryItems = Menu::factory()->count(rand(5, 12))->make(['business_id' => $business->id]);
                
                foreach ($factoryItems as $item) {
                    $data = $item->toArray();
                    $data['business_id'] = $business->id; // Ensure ID is set
                    $data['created_at'] = $now;
                    $data['updated_at'] = $now;
                    $menus[] = $data;
                }
            }
            
            // Bulk insert
            if (!empty($menus)) {
                Menu::insert($menus);
            }
        });
    }
}
