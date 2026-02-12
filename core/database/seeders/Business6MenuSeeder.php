<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class Business6MenuSeeder extends Seeder
{
    public function run(): void
    {
        $businessId = 6;

        // Ensure business exists
        $business = Business::find($businessId);
        if (! $business) {
            return;
        }

        // Clear existing menu for clean demo
        Menu::where('business_id', $businessId)->delete();

        $menuItems = [
            // Izgaralar
            ['category' => 'Izgaralar', 'name' => 'Adana Kebap', 'price' => 280, 'description' => 'Zırh kıymasıyla hazırlanan acılı kebap, közlenmiş biber ve domates ile.'],
            ['category' => 'Izgaralar', 'name' => 'Urfa Kebap', 'price' => 280, 'description' => 'Zırh kıymasıyla hazırlanan acısız kebap, özel garnitürleri ile.'],
            ['category' => 'Izgaralar', 'name' => 'Kuzu Şiş', 'price' => 320, 'description' => 'Lokum gibi kuzu eti, bulgur pilavı ve közlenmiş sebzelerle.'],
            ['category' => 'Izgaralar', 'name' => 'Karışık Izgara', 'price' => 450, 'description' => 'Adana, Şiş, Kanat ve Köfte seçkisi.'],

            // İçecekler
            ['category' => 'İçecekler', 'name' => 'Ev Yapımı Ayran', 'price' => 45, 'description' => 'Bol köpüklü, taze yoğurttan.'],
            ['category' => 'İçecekler', 'name' => 'Coca Cola', 'price' => 55, 'description' => '330ml kutu.'],
            ['category' => 'İçecekler', 'name' => 'Şalgam Suyu', 'price' => 40, 'description' => 'Adana usulü acılı veya acısız.'],
            ['category' => 'İçecekler', 'name' => 'Su', 'price' => 15, 'description' => '500ml.'],

            // Tatlılar
            ['category' => 'Tatlılar', 'name' => 'Künefe', 'price' => 140, 'description' => 'Hatay usulü, taze peynirli ve bol fıstıklı.'],
            ['category' => 'Tatlılar', 'name' => 'Fırın Sütlaç', 'price' => 90, 'description' => 'Taş fırında nar gibi kızarmış.'],
            ['category' => 'Tatlılar', 'name' => 'Fıstıklı Baklava', 'price' => 160, 'description' => 'Gaziantep usulü, 3 dilim.'],

            // Salatalar & Mezeler
            ['category' => 'Mezeler', 'name' => 'Gavurdağı Salatası', 'price' => 85, 'description' => 'İnce kıyılmış sebzeler, ceviz ve nar ekşisi ile.'],
            ['category' => 'Mezeler', 'name' => 'Humus', 'price' => 75, 'description' => 'Tereyağlı ve pastırmalı sıcak servis.'],
            ['category' => 'Mezeler', 'name' => 'Haydari', 'price' => 65, 'description' => 'Süzme yoğurt ve taze nane ile.'],
        ];

        foreach ($menuItems as $item) {
            Menu::create(array_merge($item, [
                'business_id' => $businessId,
                'is_available' => true,
            ]));
        }
    }
}
