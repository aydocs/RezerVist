<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $tags = [
            // Özellikler
            ['name' => 'Wifi', 'slug' => 'wifi', 'category' => 'Özellikler'],
            ['name' => 'Otopark', 'slug' => 'otopark', 'category' => 'Özellikler'],
            ['name' => 'Vale', 'slug' => 'vale', 'category' => 'Özellikler'],
            ['name' => 'Teras', 'slug' => 'teras', 'category' => 'Özellikler'],
            ['name' => 'Bahçe', 'slug' => 'bahce', 'category' => 'Özellikler'],
            ['name' => 'Çocuk Dostu', 'slug' => 'cocuk-dostu', 'category' => 'Özellikler'],
            ['name' => 'Evcil Hayvan Dostu', 'slug' => 'evcil-hayvan-dostu', 'category' => 'Özellikler'],
            ['name' => 'Engelli Uygun', 'slug' => 'engelli-uygun', 'category' => 'Özellikler'],
            ['name' => 'Kredi Kartı', 'slug' => 'kredi-karti', 'category' => 'Özellikler'],
            ['name' => 'Gel-Al', 'slug' => 'gel-al', 'category' => 'Özellikler'],
            ['name' => 'Paket Servis', 'slug' => 'paket-servis', 'category' => 'Özellikler'],

            // Mutfak / Tarz
            ['name' => 'İtalyan', 'slug' => 'italyan', 'category' => 'Mutfak / Konsept'],
            ['name' => 'Uzak Doğu', 'slug' => 'uzak-dogu', 'category' => 'Mutfak / Konsept'],
            ['name' => 'Ocakbaşı', 'slug' => 'ocakbasi', 'category' => 'Mutfak / Konsept'],
            ['name' => 'Deniz Mahsulleri', 'slug' => 'deniz-mahsulleri', 'category' => 'Mutfak / Konsept'],
            ['name' => 'Vegan', 'slug' => 'vegan', 'category' => 'Mutfak / Konsept'],
            ['name' => 'Vejetaryen', 'slug' => 'vejetaryen', 'category' => 'Mutfak / Konsept'],
            ['name' => 'Steakhouse', 'slug' => 'steakhouse', 'category' => 'Mutfak / Konsept'],
            ['name' => 'Cafe', 'slug' => 'cafe', 'category' => 'Mutfak / Konsept'],
            ['name' => 'Bar', 'slug' => 'bar', 'category' => 'Mutfak / Konsept'],
            ['name' => 'Meyhane', 'slug' => 'meyhane', 'category' => 'Mutfak / Konsept'],

            // Ortam
            ['name' => 'Sessiz', 'slug' => 'sessiz', 'category' => 'Ortam'],
            ['name' => 'Canlı Müzik', 'slug' => 'canli-muzik', 'category' => 'Ortam'],
            ['name' => 'Romantik', 'slug' => 'romantik', 'category' => 'Ortam'],
            ['name' => 'Manzaralı', 'slug' => 'manzarali', 'category' => 'Ortam'],
            ['name' => 'İş Yemeği', 'slug' => 'is-yemegi', 'category' => 'Ortam'],
            ['name' => 'Grup Yemeği', 'slug' => 'grup-yemegi', 'category' => 'Ortam'],
            ['name' => 'Modern & Şık', 'slug' => 'modern-sik', 'category' => 'Ortam'],
            ['name' => 'Otantik', 'slug' => 'otantik', 'category' => 'Ortam'],
            ['name' => 'Hareketli', 'slug' => 'hareketli', 'category' => 'Ortam'],
            ['name' => 'Aileye Uygun', 'slug' => 'aileye-uygun', 'category' => 'Ortam'],
            ['name' => 'Lüks / Premium', 'slug' => 'luks-premium', 'category' => 'Ortam'],
            ['name' => 'Geleneksel', 'slug' => 'geleneksel', 'category' => 'Ortam'],
            ['name' => 'Loş Işık', 'slug' => 'los-isik', 'category' => 'Ortam'],
            ['name' => 'Eğlenceli', 'slug' => 'eglenceli', 'category' => 'Ortam'],
            ['name' => 'Huzurlu', 'slug' => 'huzurlu', 'category' => 'Ortam'],
            ['name' => 'Bahçe Keyfi', 'slug' => 'bahce-keyfi', 'category' => 'Ortam'],
        ];

        foreach ($tags as $tag) {
            \App\Models\Tag::updateOrCreate(
                ['slug' => $tag['slug']],
                ['name' => $tag['name'], 'category' => $tag['category']]
            );
        }
    }
}
