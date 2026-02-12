<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Business;
use App\Models\Review;
use App\Models\Favorite;
use App\Models\Resource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========== TAGS ==========
        $this->call(TagSeeder::class);

        // ========== SAAS PACKAGES ==========
        $this->call(PackageSeeder::class);

        // ========== PREMIUM BUSINESSES (Flawless Data) ==========
        $this->call(\Database\Seeders\PremiumBusinessSeeder::class);

        // ========== USERS ==========
        $customer1 = User::updateOrCreate(
            ['email' => 'ahmet@test.com'],
            [
                'name' => 'Ahmet Yılmaz',
                'password' => Hash::make('password'),
                'phone' => '05551234567',
                'role' => 'customer',
            ]
        );

        $customer2 = User::create([
            'name' => 'Ayşe Demir',
            'email' => 'ayse@test.com',
            'password' => Hash::make('password'),
            'phone' => '05559876543',
            'role' => 'customer',
        ]);

        $customer3 = User::create([
            'name' => 'Mehmet Kaya',
            'email' => 'mehmet@test.com',
            'password' => Hash::make('password'),
            'phone' => '05551112233',
            'role' => 'customer',
        ]);

        $owner1 = User::create([
            'name' => 'İşletme Sahibi 1',
            'email' => 'owner1@test.com',
            'password' => Hash::make('password'),
            'phone' => '05552221111',
            'role' => 'business',
        ]);

        $owner2 = User::create([
            'name' => 'İşletme Sahibi 2',
            'email' => 'owner2@test.com',
            'password' => Hash::make('password'),
            'phone' => '05553332222',
            'role' => 'business',
        ]);

        // ========== CATEGORIES ==========
        $restaurant = Category::updateOrCreate(
            ['slug' => 'restoran'],
            [
                'name' => 'Restoran',
                'type' => 'place',
                'icon' => 'restaurant',
            ]
        );

        $cafe = Category::updateOrCreate(
            ['slug' => 'kafe'],
            [
                'name' => 'Kafe',
                'type' => 'place', 
                'icon' => 'cafe',
            ]
        );

        $salon = Category::updateOrCreate(
            ['slug' => 'guzellik-salonu'],
            [
                'name' => 'Güzellik Salonu',
                'type' => 'service',
                'icon' => 'salon',
            ]
        );

        $hotel = Category::updateOrCreate(
            ['slug' => 'otel'],
            [
                'name' => 'Otel',
                'type' => 'place',
                'icon' => 'hotel',
            ]
        );

        $business1 = Business::create([
            'owner_id' => $owner1->id,
            'category_id' => $restaurant->id,
            'name' => 'Kral Burger & Steak',
            'description' => 'Şehrin en lezzetli burger ve köfteleri! Premium et kalitesi, özel soslar ve taze malzemelerle hazırlanan menümüzle damak zevkinizi şaşırtacağız.',
            'address' => 'Atatürk Caddesi No:45, Kadıköy, İstanbul',
            'phone' => '02165551234',
            'rating' => 4.7,
            'is_active' => true,
        ]);

        $business2 = Business::create([
            'owner_id' => $owner1->id,
            'category_id' => $restaurant->id,
            'name' => 'Saray Sofrası',
            'description' => 'Geleneksel Türk mutfağının en seçkin lezzetleri. Annelerimizin el emeği tarifleriyle hazırlanan ev yemekleri ve kebap çeşitleri.',
            'address' => 'Cumhuriyet Meydanı No:12, Çankaya, Ankara',
            'phone' => '03125559876',
            'rating' => 4.9,
            'is_active' => true,
        ]);

        $business3 = Business::create([
            'owner_id' => $owner2->id,
            'category_id' => $restaurant->id,
            'name' => 'Deniz Mahsulleri Restaurant',
            'description' => 'Taze balık ve deniz ürünleri. Günlük taze balıklar, mezetler ve Ege mutfağının vazgeçilmez lezzetleri.',
            'address' => 'Kordon Boyu, Alsancak, İzmir',
            'phone' => '02325554321',
            'rating' => 4.6,
            'is_active' => true,
        ]);

        // ========== BUSINESSES - CAFES ==========
        $business4 = Business::create([
            'owner_id' => $owner2->id,
            'category_id' => $cafe->id,
            'name' => 'Keyif Kahve',
            'description' => 'Özel çekirdek kahveler ve ev yapımı pastalar. Huzurlu atmosferde kitap okuma köşesi ve canlı müzik.',
            'address' => 'Bağdat Caddesi No:234, Kadıköy, İstanbul',
            'phone' => '02169998877',
            'rating' => 4.5,
            'is_active' => true,
        ]);

        $business5 = Business::create([
            'owner_id' => $owner1->id,
            'category_id' => $cafe->id,
            'name' => 'Vintage Coffee House',
            'description' => 'Nostaljik dekorasyon ve özel harman kahveler. Müzik severler için plak dinleme keyfi.',
            'address' => 'Kızılay Meydanı No:8, Çankaya, Ankara',
            'phone' => '03125557788',
            'rating' => 4.8,
            'is_active' => true,
        ]);

        // ========== BUSINESSES - BEAUTY ==========
        $business6 = Business::create([
            'owner_id' => $owner2->id,
            'category_id' => $salon->id,
            'name' => 'Güzellik Uzmanı Studio',
            'description' => 'Profesyonel cilt bakımı, makyaj ve saç tasarımı. Uzman ekibimizle özel günleriniz için hazırlanın.',
            'address' => 'Nişantaşı, Teşvikiye Cad. No:67, İstanbul',
            'phone' => '02122223344',
            'rating' => 4.9,
            'is_active' => true,
        ]);

        // ========== BUSINESSES - HOTELS ==========
        $business7 = Business::create([
            'owner_id' => $owner1->id,
            'category_id' => $hotel->id,
            'name' => 'Grand Luxury Hotel',
            'description' => '5 yıldızlı konfor, spa merkezi ve gourmet restoran. İş ve tatil için mükemmel konaklama.',
            'address' => 'Taksim Meydanı, Beyoğlu, İstanbul',
            'phone' => '02122220000',
            'rating' => 4.8,
            'is_active' => true,
        ]);

        // ========== RESOURCES (Tables/Rooms) ==========
        foreach ([$business1, $business2, $business3] as $restaurant) {
            for ($i = 1; $i <= 10; $i++) {
                Resource::create([
                    'business_id' => $restaurant->id,
                    'name' => "Masa $i",
                    'type' => 'table',
                    'capacity' => rand(2, 6),
                ]);
            }
        }

        foreach ([$business4, $business5] as $cafe) {
            for ($i = 1; $i <= 8; $i++) {
                Resource::create([
                    'business_id' => $cafe->id,
                    'name' => "Masa $i",
                    'type' => 'table',
                    'capacity' => rand(2, 4),
                ]);
            }
        }

        Resource::create(['business_id' => $business6->id, 'name' => 'Bakım Kabini 1', 'type' => 'room', 'capacity' => 1]);
        Resource::create(['business_id' => $business6->id, 'name' => 'Bakım Kabini 2', 'type' => 'room', 'capacity' => 1]);
        Resource::create(['business_id' => $business6->id, 'name' => 'Makyaj Salonu', 'type' => 'room', 'capacity' => 2]);

        for ($i = 101; $i <= 110; $i++) {
            Resource::create([
                'business_id' => $business7->id,
                'name' => "Oda $i",
                'type' => 'room',
                'capacity' => 2,
            ]);
        }

        // ========== REVIEWS ==========
        Review::create([
            'business_id' => $business1->id,
            'user_id' => $customer1->id,
            'rating' => 5,
            'comment' => 'Burgerler harikaydı! Özellikle special burger çok lezzetli. Kesinlikle tekrar geleceğim.',
        ]);

        Review::create([
            'business_id' => $business1->id,
            'user_id' => $customer2->id,
            'rating' => 4,
            'comment' => 'Güzel bir mekan, fiyatlar biraz yüksek ama kalite gerçekten iyi.',
        ]);

        Review::create([
            'business_id' => $business2->id,
            'user_id' => $customer1->id,
            'rating' => 5,
            'comment' => 'Ev yemekleri çok lezzetli, annemin yemeklerini hatırlattı. Porsiyon da gayet bol.',
        ]);

        Review::create([
            'business_id' => $business2->id,
            'user_id' => $customer3->id,
            'rating' => 5,
            'comment' => 'Ankara\'nın en iyi restoranlarından biri. Her şey çok taze ve lezzetli.',
        ]);

        Review::create([
            'business_id' => $business4->id,
            'user_id' => $customer2->id,
            'rating' => 4,
            'comment' => 'Kahveleri gerçekten çok iyi, pastalar taze. Atmosfer harika.',
        ]);

        Review::create([
            'business_id' => $business6->id,
            'user_id' => $customer1->id,
            'rating' => 5,
            'comment' => 'Profesyonel ekip, harika sonuç. Düğün öncesi hazırlık için mükemmeldi!',
        ]);

        // ========== FAVORITES ==========
        Favorite::firstOrCreate(['user_id' => $customer1->id, 'business_id' => $business1->id]);
        Favorite::firstOrCreate(['user_id' => $customer1->id, 'business_id' => $business2->id]);
        Favorite::firstOrCreate(['user_id' => $customer1->id, 'business_id' => $business4->id]);
        Favorite::firstOrCreate(['user_id' => $customer2->id, 'business_id' => $business1->id]);
        Favorite::firstOrCreate(['user_id' => $customer2->id, 'business_id' => $business6->id]);

        // ========== MENUS & TAGS FOR FEATURED ==========
        $this->seedMenusAndTags([$business1, $business2, $business3, $business4, $business5, $business6, $business7]);

        // ========== MASSIVE SEEDING (81 Cities x 50 Businesses) ==========
        /*
        $this->seedMassiveBusinesses();
        */

        // ========== RICH MENU SEEDER (Images & Options) ==========
        $this->call(\Database\Seeders\RichMenuSeeder::class);

        echo "\n✅ Database seeded successfully!\n";
        echo "📧 Test Login: ahmet@test.com / password\n\n";
    }

    private function seedMenusAndTags($businesses)
    {
        $tags = \App\Models\Tag::all();
        
        foreach ($businesses as $business) {
            // Attach random tags
            $business->tags()->sync($tags->random(rand(3, 6))->pluck('id'));

            // Create some menus
            if ($business->category_id == 1 || $business->category_id == 2) { // Restaurant or Cafe
                \App\Models\Menu::create([
                    'business_id' => $business->id,
                    'category' => 'Ana Yemekler',
                    'name' => 'Özel Izgara Köfte',
                    'description' => 'Geleneksel Soslu Izgara Köfte, patates tava ile.',
                    'price' => 250.00,
                    'is_available' => true,
                ]);
                \App\Models\Menu::create([
                    'business_id' => $business->id,
                    'category' => 'İçecekler',
                    'name' => 'Ev Yapımı Limonata',
                    'description' => 'Taze nane ve limon ile hazırlanmıştır.',
                    'price' => 65.00,
                    'is_available' => true,
                ]);
            } else if ($business->category_id == 3) { // Beauty
                 \App\Models\Menu::create([
                    'business_id' => $business->id,
                    'category' => 'Cilt Bakımı',
                    'name' => 'Klasik Cilt Bakımı',
                    'description' => 'Derinlemesine temizlik ve nemlendirme.',
                    'price' => 750.00,
                    'is_available' => true,
                ]);
            }
        }
    }

    private function seedMassiveBusinesses()
    {
        $cities = [
            ['name' => 'Adana', 'lat' => 37.0000, 'lng' => 35.3213],
            ['name' => 'Adıyaman', 'lat' => 37.7648, 'lng' => 38.2786],
            ['name' => 'Afyonkarahisar', 'lat' => 38.7507, 'lng' => 30.5567],
            ['name' => 'Ağrı', 'lat' => 39.7191, 'lng' => 43.0503],
            ['name' => 'Amasya', 'lat' => 40.6499, 'lng' => 35.8353],
            ['name' => 'Ankara', 'lat' => 39.9334, 'lng' => 32.8597],
            ['name' => 'Antalya', 'lat' => 36.8969, 'lng' => 30.7133],
            ['name' => 'Artvin', 'lat' => 41.1828, 'lng' => 41.8183],
            ['name' => 'Aydın', 'lat' => 37.8444, 'lng' => 27.8458],
            ['name' => 'Balıkesir', 'lat' => 39.6484, 'lng' => 27.8826],
            ['name' => 'Bilecik', 'lat' => 40.1451, 'lng' => 29.9799],
            ['name' => 'Bingöl', 'lat' => 38.8851, 'lng' => 40.4983],
            ['name' => 'Bitlis', 'lat' => 38.4006, 'lng' => 42.1095],
            ['name' => 'Bolu', 'lat' => 40.7392, 'lng' => 31.6089],
            ['name' => 'Burdur', 'lat' => 37.7202, 'lng' => 30.2908],
            ['name' => 'Bursa', 'lat' => 40.1885, 'lng' => 29.0610],
            ['name' => 'Çanakkale', 'lat' => 40.1553, 'lng' => 26.4142],
            ['name' => 'Çankırı', 'lat' => 40.6013, 'lng' => 33.6134],
            ['name' => 'Çorum', 'lat' => 40.5506, 'lng' => 34.9556],
            ['name' => 'Denizli', 'lat' => 37.7765, 'lng' => 29.0864],
            ['name' => 'Diyarbakır', 'lat' => 37.9144, 'lng' => 40.2306],
            ['name' => 'Edirne', 'lat' => 41.6766, 'lng' => 26.5623],
            ['name' => 'Elazığ', 'lat' => 38.6748, 'lng' => 39.2225],
            ['name' => 'Erzincan', 'lat' => 39.7500, 'lng' => 39.5000],
            ['name' => 'Erzurum', 'lat' => 39.9043, 'lng' => 41.2679],
            ['name' => 'Eskişehir', 'lat' => 39.7667, 'lng' => 30.5256],
            ['name' => 'Gaziantep', 'lat' => 37.0662, 'lng' => 37.3833],
            ['name' => 'Giresun', 'lat' => 40.9128, 'lng' => 38.3895],
            ['name' => 'Gümüşhane', 'lat' => 40.4600, 'lng' => 39.4700],
            ['name' => 'Hakkari', 'lat' => 37.5833, 'lng' => 43.7333],
            ['name' => 'Hatay', 'lat' => 36.4018, 'lng' => 36.3498],
            ['name' => 'Isparta', 'lat' => 37.7648, 'lng' => 30.5566],
            ['name' => 'Mersin', 'lat' => 36.8000, 'lng' => 34.6333],
            ['name' => 'İstanbul', 'lat' => 41.0082, 'lng' => 28.9784],
            ['name' => 'İzmir', 'lat' => 38.4192, 'lng' => 27.1287],
            ['name' => 'Kars', 'lat' => 40.6167, 'lng' => 43.1000],
            ['name' => 'Kastamonu', 'lat' => 41.3887, 'lng' => 33.7827],
            ['name' => 'Kayseri', 'lat' => 38.7312, 'lng' => 35.4787],
            ['name' => 'Kırklareli', 'lat' => 41.7333, 'lng' => 27.2167],
            ['name' => 'Kırşehir', 'lat' => 39.1425, 'lng' => 34.1709],
            ['name' => 'Kocaeli', 'lat' => 40.8533, 'lng' => 29.8815],
            ['name' => 'Konya', 'lat' => 37.8667, 'lng' => 32.4833],
            ['name' => 'Kütahya', 'lat' => 39.4167, 'lng' => 29.9833],
            ['name' => 'Malatya', 'lat' => 38.3552, 'lng' => 38.3095],
            ['name' => 'Manisa', 'lat' => 38.6191, 'lng' => 27.4289],
            ['name' => 'Kahramanmaraş', 'lat' => 37.5858, 'lng' => 36.9371],
            ['name' => 'Mardin', 'lat' => 37.3212, 'lng' => 40.7245],
            ['name' => 'Muğla', 'lat' => 37.2153, 'lng' => 28.3636],
            ['name' => 'Muş', 'lat' => 38.7432, 'lng' => 41.4910],
            ['name' => 'Nevşehir', 'lat' => 38.6250, 'lng' => 34.7122],
            ['name' => 'Niğde', 'lat' => 37.9667, 'lng' => 34.6833],
            ['name' => 'Ordu', 'lat' => 40.9839, 'lng' => 37.8764],
            ['name' => 'Rize', 'lat' => 41.0201, 'lng' => 40.5234],
            ['name' => 'Sakarya', 'lat' => 40.7569, 'lng' => 30.3783],
            ['name' => 'Samsun', 'lat' => 41.2928, 'lng' => 36.3313],
            ['name' => 'Siirt', 'lat' => 37.9333, 'lng' => 41.9500],
            ['name' => 'Sinop', 'lat' => 42.0231, 'lng' => 35.1531],
            ['name' => 'Sivas', 'lat' => 39.7477, 'lng' => 37.0179],
            ['name' => 'Tekirdağ', 'lat' => 40.9833, 'lng' => 27.5167],
            ['name' => 'Tokat', 'lat' => 40.3167, 'lng' => 36.5500],
            ['name' => 'Trabzon', 'lat' => 41.0028, 'lng' => 39.7167],
            ['name' => 'Tunceli', 'lat' => 39.1079, 'lng' => 39.5401],
            ['name' => 'Şanlıurfa', 'lat' => 37.1591, 'lng' => 38.7969],
            ['name' => 'Uşak', 'lat' => 38.6823, 'lng' => 29.4082],
            ['name' => 'Van', 'lat' => 38.4891, 'lng' => 43.4089],
            ['name' => 'Yozgat', 'lat' => 39.8181, 'lng' => 34.8147],
            ['name' => 'Zonguldak', 'lat' => 41.4564, 'lng' => 31.7987],
            ['name' => 'Aksaray', 'lat' => 38.3687, 'lng' => 34.0370],
            ['name' => 'Bayburt', 'lat' => 40.2552, 'lng' => 40.2249],
            ['name' => 'Karaman', 'lat' => 37.1759, 'lng' => 33.2287],
            ['name' => 'Kırıkkale', 'lat' => 39.8468, 'lng' => 33.5153],
            ['name' => 'Batman', 'lat' => 37.8812, 'lng' => 41.1351],
            ['name' => 'Şırnak', 'lat' => 37.5164, 'lng' => 42.4611],
            ['name' => 'Bartın', 'lat' => 41.6344, 'lng' => 32.3375],
            ['name' => 'Ardahan', 'lat' => 41.1105, 'lng' => 42.7022],
            ['name' => 'Iğdır', 'lat' => 39.9196, 'lng' => 44.0450],
            ['name' => 'Yalova', 'lat' => 40.6500, 'lng' => 29.2667],
            ['name' => 'Karabük', 'lat' => 41.2061, 'lng' => 32.6204],
            ['name' => 'Kilis', 'lat' => 36.7184, 'lng' => 37.1212],
            ['name' => 'Osmaniye', 'lat' => 37.0742, 'lng' => 36.2476],
            ['name' => 'Düzce', 'lat' => 40.8438, 'lng' => 31.1565],
        ];

        // Ensure we have an owner to assign
        $owner = User::firstWhere('role', 'business') ?? User::factory()->create(['role' => 'business']);

        foreach ($cities as $city) {
            Business::factory()->count(50)->create([
                'owner_id' => $owner->id,
                'address' => function() use ($city) {
                    return fake()->streetAddress . ', ' . $city['name'];
                },
                'latitude' => function() use ($city) {
                    // Random coordinate within ~10km of city center
                    return $city['lat'] + (mt_rand(-100, 100) / 1000);
                },
                'longitude' => function() use ($city) {
                    return $city['lng'] + (mt_rand(-100, 100) / 1000);
                }
            ]);
        }
    }
}
