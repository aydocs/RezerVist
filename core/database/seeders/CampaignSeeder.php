<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampaignSeeder extends Seeder
{
    public function run()
    {
        // Clear existing campaigns
        DB::table('campaigns')->truncate();

        $campaigns = [
            [
                'title' => 'Sevgililer Günü Özeli',
                'slug' => 'sevgililer-gunu-ozeli',
                'description' => '14 Şubat\'ta sevdiklerinizle unutulmaz bir akşam yemeği için şimdiden yerinizi ayırtın. Seçili mekanlarda çiftlere özel %20 indirim fırsatını kaçırmayın. Romantik atmosfer ve özel menüler sizi bekliyor.',
                'image' => 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'discount_text' => '%20 İNDİRİM',
                'discount_code' => 'SEVGI20',
                'button_text' => 'Fırsatları İncele',
                'button_link' => '/search?category=restoran',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::parse('2026-02-15 23:59:59'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Hafta Sonu Brunch Keyfi',
                'slug' => 'hafta-sonu-brunch',
                'description' => 'Hafta sonuna harika bir başlangıç yapın! Boğaz manzaralı mekanlarda sınırsız serpme kahvaltı ve brunch keyfi sizleri bekliyor. Aileniz ve arkadaşlarınızla keyifli saatler geçirin.',
                'image' => 'https://images.unsplash.com/photo-1533777857889-4be7c70b33f7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'discount_text' => 'SINIRSIZ ÇAY',
                'discount_code' => 'KAHVALTI',
                'button_text' => 'Mekanları Gör',
                'button_link' => '/search?category=kahvalti',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addMonths(2),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Yeni Açılan Mekanları Keşfet',
                'slug' => 'yeni-mekanlar',
                'description' => 'Şehrin en yeni ve en trend mekanları Rezervist\'te! İlk rezervasyonunuza özel sürpriz ikramlar ve öncelikli masa avantajlarından yararlanmak için hemen keşfetmeye başlayın.',
                'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'discount_text' => 'SÜRPRİZ İKRAM',
                'discount_code' => 'YENI25',
                'button_text' => 'Keşfet',
                'button_link' => '/search?sort=newest',
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->addMonths(1),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('campaigns')->insert($campaigns);
    }
}
