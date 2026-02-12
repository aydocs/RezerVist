<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['name' => 'Wifi', 'slug' => 'wifi'],
            ['name' => 'Otopark', 'slug' => 'otopark'],
            ['name' => 'Vale', 'slug' => 'vale'],
            ['name' => 'Teras', 'slug' => 'teras'],
            ['name' => 'Bahçe', 'slug' => 'bahce'],
            ['name' => 'Çocuk Dostu', 'slug' => 'cocuk-dostu'],
            ['name' => 'Evcil Hayvan Dostu', 'slug' => 'evcil-hayvan-dostu'],
            ['name' => 'Engelli Uygun', 'slug' => 'engelli-uygun'],
            ['name' => 'Kredi Kartı', 'slug' => 'kredi-karti'],
            ['name' => 'Gel-Al', 'slug' => 'gel-al'],
            ['name' => 'Paket Servis', 'slug' => 'paket-servis'],
            
            // Mutfak / Tarz
            ['name' => 'İtalyan', 'slug' => 'italyan'],
            ['name' => 'Uzak Doğu', 'slug' => 'uzak-dogu'],
            ['name' => 'Ocakbaşı', 'slug' => 'ocakbasi'],
            ['name' => 'Deniz Mahsulleri', 'slug' => 'deniz-mahsulleri'],
            ['name' => 'Vegan', 'slug' => 'vegan'],
            ['name' => 'Vejetaryen', 'slug' => 'vejetaryen'],
            ['name' => 'Steakhouse', 'slug' => 'steakhouse'],
            ['name' => 'Cafe', 'slug' => 'cafe'],
            ['name' => 'Bar', 'slug' => 'bar'],
            ['name' => 'Meyhane', 'slug' => 'meyhane'],

            // Ortam
            ['name' => 'Sessiz', 'slug' => 'sessiz'],
            ['name' => 'Canlı Müzik', 'slug' => 'canli-muzik'],
            ['name' => 'Romantik', 'slug' => 'romantik'],
            ['name' => 'Manzaralı', 'slug' => 'manzarali'],
            ['name' => 'İş Yemeği', 'slug' => 'is-yemegi'],
            ['name' => 'Grup Yemeği', 'slug' => 'grup-yemegi'],
        ];

        foreach ($tags as $tag) {
            \App\Models\Tag::firstOrCreate(
                ['slug' => $tag['slug']],
                ['name' => $tag['name']]
            );
        }
    }
}
