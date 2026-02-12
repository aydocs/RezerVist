<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Restoran' => [
                'Başlangıç' => ['Mercimek Çorbası', 'Ezogelin Çorbası', 'Humus', 'Haydari', 'Sigara Böreği', 'Paçanga Böreği'],
                'Ana Yemek' => ['Adana Kebap', 'Urfa Kebap', 'İskender', 'Ali Nazik', 'Köfte', 'Tavuk Şiş', 'Kuzu Pirzola', 'Lahmacun', 'Pide'],
                'Tatlı' => ['Künefe', 'Baklava', 'Sütlaç', 'Kazandibi', 'Revani', 'Kadayıf'],
                'İçecek' => ['Ayran', 'Şalgam', 'Kola', 'Fanta', 'Çay', 'Türk Kahvesi']
            ],
            'Kafe' => [
                'Kahve' => ['Espresso', 'Latte', 'Cappuccino', 'Americano', 'Türk Kahvesi', 'Filtre Kahve'],
                'Tatlı' => ['Cheesecake', 'Tiramisu', 'Brownie', 'Trileçe', 'Waffle'],
                'Soğuk İçecek' => ['Limonata', 'Milkshake', 'Frozen', 'Ice Latte']
            ],
            'Güzellik Salonu' => [
                'Saç' => ['Saç Kesimi', 'Fön', 'Boya', 'Röfle', 'Ombre', 'Keratin Bakım'],
                'Bakım' => ['Manikür', 'Pedikür', 'Cilt Bakımı', 'Lazer Epilasyon', 'Kaş Tasarım'],
                'Makyaj' => ['Gelin Makyajı', 'Günlük Makyaj', 'Porselen Makyaj']
            ],
            'Otel' => [
                'Oda Servisi' => ['Kahvaltı Tabağı', 'Sandviç', 'Hamburger', 'Pizza', 'Salata'],
                'Hizmet' => ['Kuru Temizleme', 'Ütü', 'Masaj', 'Spa Kullanımı']
            ]
        ];

        // Default to Restoran if no business attached or generic
        $sector = 'Restoran'; 
        
        // This is a bit tricky in factory definition without state, 
        // effectively we pick random valid items.
        // In a real seeder we would match this to the business category.
        
        $flattenedItems = [];
        foreach ($categories as $sec => $cats) {
            foreach ($cats as $cat => $items) {
                foreach ($items as $item) {
                    $flattenedItems[] = ['sector' => $sec, 'category' => $cat, 'name' => $item];
                }
            }
        }
        
        $selected = $this->faker->randomElement($flattenedItems);

        return [
            'business_id' => 1, // Overridden in seeder
            'category' => $selected['category'], 
            'name' => $selected['name'],
            'description' => $this->faker->realText(50),
            'price' => $this->faker->numberBetween(50, 500),
            'calories' => $this->faker->optional()->numberBetween(100, 1000),
            'is_available' => true,
            'is_vegetarian' => $this->faker->boolean(20),
            'is_vegan' => $this->faker->boolean(10),
            'is_gluten_free' => $this->faker->boolean(10),
            // 'image' can be null or a placeholder URL
        ];
    }
}
