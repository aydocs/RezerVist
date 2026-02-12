<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Business;
use App\Models\Menu;
use App\Models\User;

class RichMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Find the business (owner1@test.com)
        $user = User::where('email', 'owner1@test.com')->first();
        if (!$user) {
            $this->command->error("User owner1@test.com not found!");
            return;
        }

        $business = Business::where('owner_id', $user->id)->first();
        if (!$business) {
            $this->command->error("Business for owner1@test.com not found!");
            return;
        }

        $this->command->info("Creating rich menu for: " . $business->name);

        // 2. Clear existing menu (optional, but good for clean slate)
        Menu::where('business_id', $business->id)->delete();

        // 3. Define extensive menu data
        $menuData = [
            'KAHVALTI VE TOSTLAR' => [
                ['name' => 'Serpme Kahvaltı (2 Kişilik)', 'price' => 850, 'desc' => 'Ezine peyniri, eski kaşar, tulum peyniri, domates, salatalık, yeşillik, bal, kaymak, tereyağı, zeytin çeşitleri, reçel, sahanda yumurta, sigara böreği, patates kızartması ve sınırsız çay ile.'],
                ['name' => 'Hızlı Kahvaltı Tabağı', 'price' => 320, 'desc' => 'Beyaz peynir, kaşar peyniri, domates, salatalık, zeytin, haşlanmış yumurta, bal, tereyağı ve 1 bardak çay.'],
                ['name' => 'Menemen', 'price' => 180, 'desc' => 'Domates, biber ve yumurta ile hazırlanan klasik lezzet.'],
                ['name' => 'Sucuklu Yumurta', 'price' => 210, 'desc' => 'Kasap sucuk ve köy yumurtası ile.'],
                ['name' => 'Kaşarlı Tost', 'price' => 140, 'desc' => 'Özel tost ekmeği, bol kaşar peyniri, domates ve salatalık söğüş ile.'],
                ['name' => 'Karışık Tost', 'price' => 160, 'desc' => 'Sucuk, kaşar peyniri, domates sosu ve turşu ile.'],
                ['name' => 'Kavurmalı Tost', 'price' => 220, 'desc' => 'Dana kavurma, kaşar peyniri ve özel baharatlar.'],
                ['name' => 'Bazlama Tost', 'price' => 170, 'desc' => 'Bazlama ekmeğine beyaz peynir, domates ve pesto sos.'],
            ],
            'BAŞLANGIÇLAR' => [
                ['name' => 'Günün Çorbası', 'price' => 90, 'desc' => 'Mevsim sebzeleri ile hazırlanan günlük taze çorba.'],
                ['name' => 'Paçanga Böreği', 'price' => 150, 'desc' => 'Pastırma, kaşar peyniri, domates ve biber ile.'],
                ['name' => 'Patates Tava', 'price' => 110, 'desc' => 'Baharatlı elma dilim veya parmak patates, özel sos ile.'],
                ['name' => 'Soğan Halkaları (8 Adet)', 'price' => 120, 'desc' => 'Çıtır kaplamalı soğan halkaları, barbekü sos ile.'],
                ['name' => 'Mozzarella Sticks', 'price' => 140, 'desc' => 'Panelenmiş mozzarella çubukları, marinara sos ile.'],
                ['name' => 'Combo Sepeti', 'price' => 280, 'desc' => 'Soğan halkası, sosis, patates, sigara böreği, tavuk parçaları.'],
            ],
            'ANA YEMEKLER' => [
                ['name' => 'Izgara Köfte', 'price' => 340, 'desc' => 'Dana kıymasından özel baharatlarla, pilav ve közlenmiş sebzelerle.'],
                ['name' => 'Çökertme Kebabı', 'price' => 450, 'desc' => 'Kibrit patates yatağında bonfile dilimleri, sarımsaklı yoğurt ve domates sos.'],
                ['name' => 'Köri Soslu Tavuk', 'price' => 290, 'desc' => 'Jülyen tavuk, mantar, renkli biberler, kremsi köri sos, pilav eşliğinde.'],
                ['name' => 'Mexican Tavuk', 'price' => 310, 'desc' => 'Acılı meksika sosu, meksika fasulyesi, mısır, biberler, pilav ile.'],
                ['name' => 'Dana Bonfile', 'price' => 580, 'desc' => '200gr dana bonfile, patates püresi ve kuşkonmaz ile.'],
                ['name' => 'Somon Izgara', 'price' => 480, 'desc' => 'Taze somon fileto, roka salatası ve fırınlanmış bebek patates.'],
                ['name' => 'Tavuk Schnitzel', 'price' => 280, 'desc' => 'Panelenmiş tavuk göğsü, patates salatası ve limonlu tereyağı sos.'],
                ['name' => 'Mantı', 'price' => 260, 'desc' => 'Ev yapımı mantı, sarımsaklı yoğurt, tereyağlı pul biber sosu.'],
            ],
            'BURGER & PİZZA' => [
                ['name' => 'Klasik Burger', 'price' => 290, 'desc' => '140gr dana köfte, cheddar, marul, domates, turşu, patates kızartması.'],
                ['name' => 'Cheeseburger', 'price' => 310, 'desc' => '140gr dana köfte, çift cheddar, karamelize soğan, özel sos.'],
                ['name' => 'Mushroom Burger', 'price' => 330, 'desc' => '140gr dana köfte, mantar sote, swiss peyniri, trüf mayonez.'],
                ['name' => 'Margherita Pizza', 'price' => 260, 'desc' => 'Mozzarella peyniri, özel domates sosu, taze fesleğen.'],
                ['name' => 'Pepperoni Pizza', 'price' => 300, 'desc' => 'Mozzarella, domates sos, İtalyan salamı.'],
                ['name' => 'Karışık Pizza', 'price' => 320, 'desc' => 'Sucuk, sosis, mantar, mısır, biber, zeytin, mozzarella.'],
                ['name' => 'Dört Peynirli Pizza', 'price' => 340, 'desc' => 'Mozzarella, gorgonzola, parmesan, cheddar.'],
            ],
            'SALATALAR & MAKARNALAR' => [
                ['name' => 'Sezar Salata', 'price' => 240, 'desc' => 'Göbek marul, ızgara tavuk, kruton ekmek, parmesan, sezar sos.'],
                ['name' => 'Akdeniz Salata', 'price' => 210, 'desc' => 'Mevsim yeşillikleri, beyaz peynir, zeytin, mısır, nar ekşisi sos.'],
                ['name' => 'Ton Balıklı Salata', 'price' => 260, 'desc' => 'Roka, marul, ton balığı, kapari, kırmızı soğan, limon sos.'],
                ['name' => 'Penne Arrabbiata', 'price' => 230, 'desc' => 'Acılı domates sos, siyah zeytin, maydanoz, parmesan.'],
                ['name' => 'Fettuccine Alfredo', 'price' => 250, 'desc' => 'Krema, mantar, tavuk parçaları, parmesan peyniri.'],
                ['name' => 'Spaghetti Bolognese', 'price' => 260, 'desc' => 'Kıyma soslu klasik spagetti.'],
            ],
            'TATLILAR & PASTALAR' => [
                ['name' => 'San Sebastian Cheesecake', 'price' => 180, 'desc' => 'Yanık cheesecake, isteğe göre çikolata sos ile.'],
                ['name' => 'Frambuazlı Cheesecake', 'price' => 170, 'desc' => 'Klasik cheesecake, frambuaz soslu.'],
                ['name' => 'Tiramisu', 'price' => 160, 'desc' => 'Mascarpone peyniri ve espresso ile hazırlanan İtalyan tatlısı.'],
                ['name' => 'Profiterol', 'price' => 150, 'desc' => 'Çikolata soslu, krema dolgulu top hamurlar.'],
                ['name' => 'Çikolatalı Sufle', 'price' => 160, 'desc' => 'Sıcak akışkan çikolatalı kek, bir top dondurma ile.'],
                ['name' => 'Waffle', 'price' => 190, 'desc' => 'Mevsim meyveleri, çikolata sos, fındık, fıstık.'],
                ['name' => 'Fıstıklı Baklava (Porsiyon)', 'price' => 220, 'desc' => 'Gaziantep usulü bol fıstıklı baklava, 3 dilim.'],
                ['name' => 'Dondurma (Top)', 'price' => 40, 'desc' => 'Vanilya, Çikolata, Çilek, Limon, Fıstık.'],
            ],
            'SICAK İÇECEKLER' => [
                ['name' => 'Çay (Bardak)', 'price' => 25, 'desc' => 'Taze demlenmiş Rize çayı.'],
                ['name' => 'Çay (Fincan)', 'price' => 35, 'desc' => 'Büyük fincan çay.'],
                ['name' => 'Türk Kahvesi', 'price' => 70, 'desc' => 'Geleneksel Türk kahvesi, lokum ile.'],
                ['name' => 'Filtre Kahve', 'price' => 80, 'desc' => 'Taze çekilmiş çekirdekten.'],
                ['name' => 'Latte', 'price' => 90, 'desc' => 'Espresso ve sıcak süt.'],
                ['name' => 'Cappuccino', 'price' => 90, 'desc' => 'Espresso ve süt köpüğü.'],
                ['name' => 'Americano', 'price' => 80, 'desc' => 'Sıcak su ile inceltilmiş espresso.'],
                ['name' => 'Sahlep', 'price' => 95, 'desc' => 'Tarçın ile, kış aylarının vazgeçilmezi.'],
            ],
            'SOĞUK İÇECEKLER' => [
                ['name' => 'Su (330ml)', 'price' => 20, 'desc' => 'Cam şişe su.'],
                ['name' => 'Coca Cola', 'price' => 60, 'desc' => '330ml kutu.'],
                ['name' => 'Fanta', 'price' => 60, 'desc' => '330ml kutu.'],
                ['name' => 'Sprite', 'price' => 60, 'desc' => '330ml kutu.'],
                ['name' => 'Ayran', 'price' => 45, 'desc' => 'Ev yapımı bol köpüklü ayran.'],
                ['name' => 'Limonata', 'price' => 80, 'desc' => 'Ev yapımı, nane yaprakları ile.'],
                ['name' => 'Churchill', 'price' => 75, 'desc' => 'Soda, limon suyu ve tuz.'],
                ['name' => 'Ice Latte', 'price' => 100, 'desc' => 'Soğuk süt ve espresso, buzlu.'],
                ['name' => 'Milkshake', 'price' => 120, 'desc' => 'Çilek, Çikolata veya Vanilya.'],
                ['name' => 'Taze Portakal Suyu', 'price' => 90, 'desc' => 'Sıkma portakal suyu.'],
            ]
        ];

        foreach ($menuData as $category => $items) {
            foreach ($items as $item) {
                // Assign image based on category
                $imagePath = null;
                
                // Default mapping
                switch ($category) {
                    case 'KAHVALTI VE TOSTLAR':
                        $imagePath = ($item['name'] === 'Serpme Kahvaltı (2 Kişilik)') ? 'menus/serpme_kahvalti.png' : 'menus/karisik_tost.png';
                        break;
                    case 'BAŞLANGIÇLAR':
                        $imagePath = 'menus/mercimek_corbasi.png';
                        break;
                    case 'ANA YEMEKLER':
                        $imagePath = 'menus/izgara_kofte.png';
                        break;
                    case 'BURGER & PİZZA':
                        $imagePath = 'menus/hamburger.png';
                        break;
                    case 'SALATALAR & MAKARNALAR':
                        $imagePath = 'menus/izgara_kofte.png'; // Placeholder for now
                        break;
                    case 'TATLILAR & PASTALAR':
                        $imagePath = 'menus/cheesecake.png';
                        break;
                    case 'SICAK İÇECEKLER':
                    case 'SOĞUK İÇECEKLER':
                        // No image for drinks for now
                        $imagePath = null;
                        break;
                    default:
                        $imagePath = null;
                }
                
                // Add specific options for certain items
                $options = null;
                if ($item['name'] === 'Çay (Bardak)' || $item['name'] === 'Çay (Fincan)') {
                    $options = [
                        [
                            'group_name' => 'Dem Oranı',
                            'type' => 'radio', // single select
                            'required' => true,
                            'items' => [
                                ['name' => 'Açık', 'price_diff' => 0],
                                ['name' => 'Normal', 'price_diff' => 0],
                                ['name' => 'Demli', 'price_diff' => 0]
                            ]
                        ]
                    ];
                } elseif ($item['name'] === 'Izgara Köfte') {
                     $options = [
                        [
                            'group_name' => 'Porsiyon',
                            'type' => 'radio',
                            'required' => true,
                            'items' => [
                                ['name' => '1 Porsiyon (200gr)', 'price_diff' => 0],
                                ['name' => '1.5 Porsiyon (300gr)', 'price_diff' => 170]
                            ]
                        ],
                        [
                            'group_name' => 'Pişirme Derecesi',
                            'type' => 'radio',
                            'required' => false,
                            'items' => [
                                ['name' => 'Az Pişmiş', 'price_diff' => 0],
                                ['name' => 'Orta Pişmiş', 'price_diff' => 0],
                                ['name' => 'İyi Pişmiş', 'price_diff' => 0]
                            ]
                        ]
                    ];
                }

                Menu::create([
                    'business_id' => $business->id,
                    'name' => $item['name'],
                    'category' => $category,
                    'price' => $item['price'],
                    'description' => $item['desc'],
                    'stock_enabled' => rand(0, 1),
                    'stock_quantity' => rand(10, 100),
                    'is_available' => true,
                    'image' => $imagePath,
                    'options' => $options
                ]);
            }
        }
        
        $this->command->info("Rich menu created with " . count($menuData) . " categories!");
    }
}
