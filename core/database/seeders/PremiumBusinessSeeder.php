<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessDay;
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
            'password' => bcrypt('password')
        ]);

        // Ensure Categories Exist
        $this->ensureCategories();

        $this->createSaltBaeSteakhouse($owner);
        $this->createLaDivaSpa($owner);
        $this->createSkyLoungeAndBar($owner);
        $this->createGrandHotel($owner);
        $this->createGourmetFish($owner);
    }

    private function ensureCategories()
    {
        $categories = [
            ['name' => 'Restoran', 'slug' => 'restoran', 'type' => 'place', 'icon' => 'restaurant'],
            ['name' => 'Kafe', 'slug' => 'kafe', 'type' => 'place', 'icon' => 'cafe'],
            ['name' => 'Güzellik Salonu', 'slug' => 'guzellik-salonu', 'type' => 'service', 'icon' => 'salon'],
            ['name' => 'Otel', 'slug' => 'otel', 'type' => 'place', 'icon' => 'hotel'],
            ['name' => 'Eğlence', 'slug' => 'eglence', 'type' => 'place', 'icon' => 'nightlife'],
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

    private function createLaDivaSpa($owner)
    {
        $category = Category::where('slug', 'guzellik-salonu')->first();

        $business = Business::create([
            'owner_id' => $owner->id,
            'category_id' => $category->id,
            'name' => 'La Diva Spa & Wellness',
            'slug' => 'la-diva-spa-wellness',
            'description' => 'Şehrin gürültüsünden uzaklaşın. Geleneksel Türk hamamı, Bali masajı ve cilt bakımı hizmetlerimizle yenilenin.',
            'address' => 'Bağdat Caddesi No:220, Suadiye, İstanbul',
            'latitude' => 40.9632,
            'longitude' => 29.0860,
            'phone' => '02164445566',
            'rating' => 4.8,
            'is_active' => true,
            'price_per_person' => 1500,
        ]);

         // Hours (Tue-Sun 10:00 - 20:00, Mon Closed)
         for ($i = 0; $i <= 6; $i++) {
            BusinessHour::create([
                'business_id' => $business->id,
                'day_of_week' => $i,
                'open_time' => '10:00',
                'close_time' => '20:00',
                'is_closed' => ($i === 1), // Monday Closed
            ]);
        }

        $images = [
            'https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=1200', // Spa
            'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=1200', // Massage
        ];
        
        foreach ($images as $url) {
            BusinessImage::create(['business_id' => $business->id, 'image_path' => $url]);
        }

        // Resources
        Resource::create(['business_id' => $business->id, 'name' => 'Masaj Odası 1 (Bamboo)', 'type' => 'room', 'capacity' => 1]);
        Resource::create(['business_id' => $business->id, 'name' => 'Masaj Odası 2 (Lotus)', 'type' => 'room', 'capacity' => 1]);
        Resource::create(['business_id' => $business->id, 'name' => 'VIP Hamam', 'type' => 'room', 'capacity' => 2]);

        Menu::create([
            'business_id' => $business->id,
            'category' => 'Masaj',
            'name' => 'Klasik Bali Masajı (60 Dk)',
            'description' => 'Aromatik yağlarla yapılan rahatlatıcı tüm vücut masajı.',
            'price' => 1200,
            'is_available' => true,
        ]);
        Menu::create([
            'business_id' => $business->id,
            'category' => 'Cilt Bakımı',
            'name' => 'Hydrafacial',
            'description' => 'Derinlemesine cilt temizliği ve anti-aging bakım.',
            'price' => 1500,
            'is_available' => true,
        ]);
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
        for($i=1; $i<=15; $i++) {
            Resource::create(['business_id' => $business->id, 'name' => "Bistro $i", 'type' => 'bistro', 'capacity' => 2]);
        }
        for($i=1; $i<=5; $i++) {
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

    private function createGrandHotel($owner)
    {
        $category = Category::where('slug', 'otel')->first();

        $business = Business::create([
            'owner_id' => $owner->id,
            'category_id' => $category->id,
            'name' => 'Ciragan Palace Kempinski',
            'slug' => 'ciragan-palace',
            'description' => 'Boğazın kıyısında tarihi bir sarayda konaklama deneyimi. 5 çayı, sonsuzluk havuzu ve kral dairesi.',
            'address' => 'Çırağan Caddesi No:32, Beşiktaş, İstanbul',
            'latitude' => 41.0438,
            'longitude' => 29.0163,
            'phone' => '02123264646',
            'rating' => 5.0,
            'is_active' => true,
            'price_per_person' => 10000,
        ]);

        for ($i = 0; $i <= 6; $i++) {
            BusinessHour::create([
                'business_id' => $business->id,
                'day_of_week' => $i,
                'open_time' => '00:00',
                'close_time' => '23:59',
                'is_closed' => false,
            ]);
        }

        BusinessImage::create(['business_id' => $business->id, 'image_path' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1200']); // Hotel

        Menu::create([
            'business_id' => $business->id,
            'category' => 'Konaklama',
            'name' => 'Deluxe Boğaz Manzaralı Oda',
            'description' => '35m2, balkonlu ve panoramik boğaz manzaralı.',
            'price' => 25000,
            'is_available' => true,
        ]);
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
