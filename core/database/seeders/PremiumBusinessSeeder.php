<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessHour;
use App\Models\BusinessImage;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Resource;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;

class PremiumBusinessSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::firstWhere('role', 'business') ?? User::factory()->create([
            'name' => 'Premium İşletme Sahibi',
            'email' => 'premium@rezervist.com',
            'role' => 'business',
            'password' => bcrypt('password'),
        ]);

        // Ensure Categories Exist
        $this->ensureCategories();

        $this->createSaltBaeSteakhouse($owner);
        $this->createSkyLoungeAndBar($owner);
        $this->createGourmetFish($owner);
    }

    private function ensureCategories()
    {
        $categories = [
            ['name' => 'Restoran', 'slug' => 'restoran', 'type' => 'place', 'icon' => 'restaurant'],
            ['name' => 'Kafe', 'slug' => 'kafe', 'type' => 'place', 'icon' => 'cafe'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }

    private function createSaltBaeSteakhouse($owner)
    {
        $category = Category::where('slug', 'restoran')->first();

        $business = Business::create([
            'owner_id' => $owner->id,
            'category_id' => $category->id,
            'name' => 'Nusret Steakhouse Etiler',
            'slug' => 'nusret-steakhouse-etiler',
            'description' => 'Dünyaca ünlü et restoranı. Eşsiz dry-aged etleri, özel sunumları ve warm atmosphere with luxury service.',
            'address' => 'Nispetiye Caddesi No:87, Etiler, İstanbul',
            'latitude' => 41.0805,
            'longitude' => 29.0345,
            'phone' => '02123583022',
            'rating' => 4.9,
            'is_active' => true,
            'price_per_person' => 2500,
            'waiter_kiosk_enabled' => true,
        ]);

        // Hours (Everyday 12:00 - 00:00)
        for ($i = 0; $i <= 6; $i++) {
            BusinessHour::create([
                'business_id' => $business->id,
                'day_of_week' => $i,
                'open_time' => '12:00',
                'close_time' => '00:00',
                'is_closed' => false,
            ]);
        }

        // Images
        $images = [
            'https://images.unsplash.com/photo-1544025162-d76690b6d029?q=80&w=1200', // Steak
            'https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1200', // Interior
            'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200', // Ambience
        ];

        foreach ($images as $url) {
            BusinessImage::create([
                'business_id' => $business->id,
                'image_path' => $url,
            ]);
        }

        // Resources (Tables)
        // Main Hall
        for ($i = 1; $i <= 10; $i++) {
            Resource::create([
                'business_id' => $business->id,
                'name' => "Salon Masa $i",
                'type' => 'table',
                'capacity' => 4,
            ]);
        }
        // VIP Room
        Resource::create(['business_id' => $business->id, 'name' => 'VIP King Table', 'type' => 'vip_room', 'capacity' => 12]);
        Resource::create(['business_id' => $business->id, 'name' => 'VIP Queen Table', 'type' => 'vip_room', 'capacity' => 8]);

        // Menu (Starters)
        Menu::create([
            'business_id' => $business->id,
            'category' => 'Başlangıçlar',
            'name' => 'Dana Carpaccio',
            'description' => 'İnce dilimlenmiş çiğ dana fileto, roka ve parmesan ile.',
            'price' => 850,
            'is_available' => true,
        ]);

        // Menu (Main)
        Menu::create([
            'business_id' => $business->id,
            'category' => 'Ana Yemekler',
            'name' => 'Nusret Special',
            'description' => 'Tereyağında pişirilmiş özel dilim bonfile.',
            'price' => 3200,
            'is_available' => true,
        ]);
        Menu::create([
            'business_id' => $business->id,
            'category' => 'Ana Yemekler',
            'name' => 'Dallas Steak',
            'description' => 'Dry-aged, kemikli 500gr antrikot.',
            'price' => 2800,
            'is_available' => true,
        ]);

        // Staff
        Staff::create(['business_id' => $business->id, 'name' => 'Gökçe', 'position' => 'Head Chef']);
        Staff::create(['business_id' => $business->id, 'name' => 'Mehmet Şef', 'position' => 'Sous Chef']);
    }

    private function createSkyLoungeAndBar($owner)
    {
        $category = Category::where('slug', 'kafe')->first() ?? Category::first();

        $business = Business::create([
            'owner_id' => $owner->id,
            'category_id' => $category->id,
            'name' => 'Sky Lounge & Bar 360',
            'slug' => 'sky-lounge-bar-360',
            'description' => 'İstanbul ayaklarınızın altında. Panoramik boğaz manzarası, imza kokteyller ve DJ performansları.',
            'address' => 'İstiklal Caddesi Mısır Apt. Kat:8, Beyoğlu, İstanbul',
            'latitude' => 41.0335,
            'longitude' => 28.9778,
            'phone' => '02122511234',
            'rating' => 4.7,
            'is_active' => true,
            'price_per_person' => 1000,
        ]);

        for ($i = 0; $i <= 6; $i++) {
            BusinessHour::create([
                'business_id' => $business->id,
                'day_of_week' => $i,
                'open_time' => '16:00',
                'close_time' => '02:00',
                'is_closed' => false,
            ]);
        }

        BusinessImage::create(['business_id' => $business->id, 'image_path' => 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=1200']); // Cocktail
        BusinessImage::create(['business_id' => $business->id, 'image_path' => 'https://images.unsplash.com/photo-1566417713204-38c9e7218e15?q=80&w=1200']); // Bar

        // Resources (Bistros)
        for ($i = 1; $i <= 15; $i++) {
            Resource::create(['business_id' => $business->id, 'name' => "Bistro $i", 'type' => 'bistro', 'capacity' => 2]);
        }
        for ($i = 1; $i <= 5; $i++) {
            Resource::create(['business_id' => $business->id, 'name' => "Loca $i", 'type' => 'lounge', 'capacity' => 6]);
        }

        Menu::create([
            'business_id' => $business->id,
            'category' => 'Kokteyl',
            'name' => 'Sunset Dreams',
            'description' => 'Cin, mürver çiçeği likörü, lime ve taze salatalık.',
            'price' => 450,
            'is_available' => true,
        ]);
        Menu::create([
            'business_id' => $business->id,
            'category' => 'Atıştırmalık',
            'name' => 'Trüflü Patates Kızartması',
            'description' => 'Parmesan peyniri ve trüf yağı ile.',
            'price' => 300,
            'is_available' => true,
        ]);
    }

    }

    private function createGourmetFish($owner)
    {
        $category = Category::where('slug', 'restoran')->first();

        $business = Business::create([
            'owner_id' => $owner->id,
            'category_id' => $category->id,
            'name' => 'Park Fora',
            'slug' => 'park-fora-fish',
            'description' => 'Kuruçeşme parkının içinde, denize sıfır balık keyfi. Günlük taze balıklar ve efsanevi sufle.',
            'address' => 'Muallim Naci Cad. No:54/A, Kuruçeşme, İstanbul',
            'latitude' => 41.0560,
            'longitude' => 29.0322,
            'phone' => '02122655063',
            'rating' => 4.5,
            'is_active' => true,
            'price_per_person' => 2000,
        ]);

        for ($i = 0; $i <= 6; $i++) {
            BusinessHour::create([
                'business_id' => $business->id,
                'day_of_week' => $i,
                'open_time' => '12:00',
                'close_time' => '23:00',
                'is_closed' => false,
            ]);
        }

        BusinessImage::create(['business_id' => $business->id, 'image_path' => 'https://images.unsplash.com/photo-1599488615731-7e51281906ef?q=80&w=1200']); // Fish

        Resource::create(['business_id' => $business->id, 'name' => 'Deniz Kenarı Masa 1', 'type' => 'table', 'capacity' => 4]);
        Resource::create(['business_id' => $business->id, 'name' => 'Deniz Kenarı Masa 2', 'type' => 'table', 'capacity' => 4]);
        Resource::create(['business_id' => $business->id, 'name' => 'İç Salon Masa 1', 'type' => 'table', 'capacity' => 6]);

        Menu::create([
            'business_id' => $business->id,
            'category' => 'Balıklar',
            'name' => 'Tuzda Levrek (2 Kişilik)',
            'description' => 'Deniz tuzu ile fırınlanmış, alevli sunum ile servis edilir.',
            'price' => 2800,
            'is_available' => true,
        ]);
        Menu::create([
            'business_id' => $business->id,
            'category' => 'Tatlı',
            'name' => 'Volkanik Sufle',
            'description' => 'Çikolata şelalesi, yanında vanilyalı dondurma ile.',
            'price' => 400,
            'is_available' => true,
        ]);
    }
}
