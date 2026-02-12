<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleBlogSeeder extends Seeder
{
    public function run()
    {
        // Ensure a category exists
        $category = PostCategory::firstOrCreate(
            ['name' => 'Teknoloji'],
            ['slug' => 'teknoloji', 'description' => 'Teknoloji haberleri ve incelemeleri.']
        );

        // Ensure a user exists (author)
        $author = User::first() ?? User::factory()->create();

        // Create a sample post
        $title = 'RezerVist ile İşletmenizi Dijitalleştirin';
        $post = Post::create([
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => '<p><strong>RezerVist</strong>, işletmenizin rezervasyon süreçlerini otomatize eden modern bir çözümdür. Müşterileriniz 7/24 randevu alabilir, siz de işinize odaklanabilirsiniz.</p><h2>Neden RezerVist?</h2><ul><li>Kolay kullanım</li><li>Hızlı entegrasyon</li><li>Gelişmiş raporlama</li></ul><p>Hemen bugün ücretsiz deneyin!</p>',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'is_published' => true,
            'published_at' => now(),
            'meta_title' => 'RezerVist - İşletmenizi Büyütün',
            'meta_description' => 'RezerVist rezervasyon sistemi ile işletmenizi dijital dünyaya taşıyın. Kolay randevu yönetimi ve müşteri takibi.',
            'featured_image' => null, // No image for this test
        ]);

        $this->command->info("Blog post '{$post->title}' created successfully.");
    }
}
