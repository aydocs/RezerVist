<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run()
    {
        // Temizle
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Post::truncate();
        PostCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Admin Kullanıcısı
        $author = User::first() ?? User::factory()->create();

        // Kategoriler
        $categoriesInput = [
            'Sistem Özellikleri' => 'RezerVist panelinin güçlü özelliklerini keşfedin.',
            'İşletme Yönetimi' => 'İşletmenizi büyütmek için ipuçları ve stratejiler.',
            'Dijitalleşme' => 'Kağıt-kalemden dijital dünyaya geçiş rehberleri.',
            'Müşteri İlişkileri' => 'Müşteri memnuniyetini artırmanın yolları.',
        ];

        $categories = [];
        foreach ($categoriesInput as $name => $desc) {
            $categories[$name] = PostCategory::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $desc,
            ]);
        }

        // Blog Yazıları (Sistem Odaklı)
        $posts = [
            [
                'title' => 'Randevu Kaçırmalarına Son: SMS Bildirimleri',
                'category' => 'Sistem Özellikleri',
                'image' => 'blog/post_1.jpg',
                'content' => '
                    <p>Müşterilerinizin randevularını unutması, işletmeniz için hem zaman hem de gelir kaybıdır. RezerVist\'in otomatik SMS bildirim sistemi sayesinde bu sorun tarih oluyor.</p>
                    
                    <h3>Nasıl Çalışır?</h3>
                    <p>Müşteriniz randevu oluşturduğunda anında bir onay SMS\'i alır. Randevu saatinden 24 saat ve 2 saat önce gönderilen hatırlatma mesajlarıyla katılım oranı %90\'a kadar artar.</p>

                    <blockquote>"Otomatik hatırlatmalar sayesinde iptal oranlarımız %40 azaldı." - Pilot İşletme Sahibi</blockquote>

                    <h3>Özelleştirilebilir Mesajlar</h3>
                    <p>Panel üzerinden mesaj şablonlarını işletmenizin diline göre düzenleyebilir, özel kampanya notları ekleyebilirsiniz.</p>
                ',
            ],
            [
                'title' => 'Gelişmiş Finansal Raporlar ile Gelirinizi Takip Edin',
                'category' => 'İşletme Yönetimi',
                'image' => 'blog/post_2.jpg',
                'content' => '
                    <p>Hangi hizmet daha çok kazandırıyor? Hangi personel daha verimli? RezerVist\'in detaylı raporlama araçlarıyla işletmenizin röntgenini çekin.</p>

                    <h2>Neleri Raporlayabilirsiniz?</h2>
                    <ul>
                        <li><strong>Günlük/Aylık Ciro:</strong> Nakit, kredi kartı ve diğer ödeme yöntemlerine göre kırılımlar.</li>
                        <li><strong>Hizmet Performansı:</strong> En çok tercih edilen ve en karlı işlemler.</li>
                        <li><strong>Personel Verimliliği:</strong> Çalışan bazlı işlem sayısı ve ciro analizi.</li>
                    </ul>
                    
                    <p>Veriye dayalı kararlar alarak işletmenizi karlı bir şekilde büyütün.</p>
                ',
            ],
            [
                'title' => 'Mobil Uygulama İle İşletmeniz Cebinizde',
                'category' => 'Sistem Özellikleri',
                'image' => 'blog/post_3.jpg',
                'content' => '
                    <p>İşletmenizi yönetmek için bilgisayar başında olmanıza gerek yok. RezerVist Mobil Uygulaması ile özgürlüğün tadını çıkarın.</p>
                    
                    <h3>Özellikler</h3>
                    <ul>
                        <li><strong>Anlık Bildirimler:</strong> Yeni randevu veya iptallerden anında haberdar olun.</li>
                        <li><strong>Takvim Yönetimi:</strong> Hareket halindeyken randevuları düzenleyin, onaylayın.</li>
                        <li><strong>Hızlı Satış:</strong> POS özelliği ile cepten adisyon açın.</li>
                    </ul>
                ',
            ],
            [
                'title' => 'Neden Personeliniz İçin Dijital Takvim Kullanmalısınız?',
                'category' => 'Dijitalleşme',
                'image' => 'blog/post_4.jpg',
                'content' => '
                    <p>Kara kaplı defterler ve silinip yazılan randevular karmaşaya yol açar. Dijital takvim, ekibinizin uyum içinde çalışmasını sağlar.</p>
                    
                    <h3>Çakışmaları Önleyin</h3>
                    <p>Sistem, dolu saatlere randevu verilmesini otomatik olarak engeller. Böylece "çifte rezervasyon" hatası yaşanmaz.</p>
                    
                    <h3>Mola ve İzin Yönetimi</h3>
                    <p>Personelinizin yemek molalarını ve izin günlerini sisteme tanımlayarak, o saatlerin müşteriye kapalı görünmesini sağlayın.</p>
                ',
            ],
            [
                'title' => 'Online Ödeme Entegrasyonu: Güvenli ve Hızlı',
                'category' => 'Sistem Özellikleri',
                'image' => 'blog/post_5.jpg',
                'content' => '
                    <p>No-Show (Randevuya gelmeme) oranlarını azaltmanın en etkili yolu ön ödeme almaktır. RezerVist, güvenli ödeme altyapısıyla bunu kolaylaştırır.</p>
                    
                    <p>Müşterileriniz randevu alırken hizmet bedelinin tamamını veya bir kısmını kapora olarak ödeyebilir. Bu sayede randevu ciddiyeti artar ve geliriniz garanti altına alınır.</p>
                ',
            ],
            [
                'title' => 'Müşteri Sadakati: CRM Özellikleri',
                'category' => 'Müşteri İlişkileri',
                'image' => 'blog/post_6.jpg',
                'content' => '
                    <p>Yeni müşteri bulmak, mevcut müşteriyi tutmaktan 5 kat daha maliyetlidir. CRM modülü ile müşterilerinizi yakından tanıyın.</p>
                    
                    <h3>Müşteri Profili</h3>
                    <p>Her müşterinin aldığı hizmet geçmişini, notlarını (örneğin: "Kahve sevmiyor, çay ikram edin") ve ödeme alışkanlıklarını tek ekranda görüntüleyin.</p>
                ',
            ],
            [
                'title' => '7/24 Randevu: Siz Uyurken İşletmeniz Çalışsın',
                'category' => 'Dijitalleşme',
                'image' => 'blog/post_7.jpg',
                'content' => '
                    <p>Telefon trafiğinden kurtulun. Müşterileriniz gecenin 3\'ünde bile web siteniz veya uygulamanız üzerinden müsaitlik durumunu görüp randevu alabilsin.</p>
                    
                    <p>Bu özellik sayesinde randevu alma bariyerleri kalkar ve potansiyel müşteri kaybı önlenir. Sanal asistanınız RezerVist, 7/24 resepsiyon görevi görür.</p>
                ',
            ],
            [
                'title' => 'Stok Takibi ile Maliyetleri Kontrol Altına Alın',
                'category' => 'İşletme Yönetimi',
                'image' => 'blog/post_8.jpg',
                'content' => '
                    <p>Kullandığınız ürünlerin takibini yapmak kar marjınızı artırır. Hangi boyadan ne kadar kullanıldı? Şampuan stoku bitiyor mu?</p>
                    
                    <p>Hizmetlerle ürünleri eşleştirerek, her işlem yapıldığında stoktan otomatik düşülmesini sağlayın. Kritik stok seviyesine gelindiğinde uyarı alın.</p>
                ',
            ],
            [
                'title' => 'Web Siteniz: İşletmenizin Dijital Vitrini',
                'category' => 'Dijitalleşme',
                'image' => 'blog/post_9.jpg',
                'content' => '
                    <p>RezerVist, size sadece bir panel değil, aynı zamanda modern ve SEO uyumlu bir web sitesi sunar. İşletmenizin fotoğraflarını, ekibini ve hizmetlerini en şık şekilde sergileyin.</p>
                    
                    <p>Google haritalar entegrasyonu ve sosyal medya bağlantılarıyla müşterilerinizin size ulaşmasını kolaylaştırın.</p>
                ',
            ],
            [
                'title' => 'Yapay Zeka Destekli Asistan (Pek Yakında)',
                'category' => 'Sistem Özellikleri',
                'image' => 'blog/post_10.jpg',
                'content' => '
                    <p>Teknoloji durmuyor, biz de durmuyoruz. Çok yakında yapay zeka özelliklerimizle tanışacaksınız.</p>
                    
                    <ul>
                        <li><strong>Akıllı Öneriler:</strong> Boş saatleri doldurmak için kampanya önerileri.</li>
                        <li><strong>Chatbot:</strong> Müşteri sorularını otomatik yanıtlayan sanal asistan.</li>
                    </ul>
                ',
            ],
        ];

        foreach ($posts as $post) {
            Post::create([
                'title' => $post['title'],
                'slug' => Str::slug($post['title']),
                'content' => $post['content'],
                'category_id' => $categories[$post['category']]->id,
                'author_id' => $author->id,
                'is_published' => true,
                'is_featured' => rand(0, 1) == 1,
                'published_at' => now()->subDays(rand(1, 30)),
                'views' => rand(100, 2500),
                'featured_image' => $post['image'],
                'meta_title' => $post['title'],
                'meta_description' => Str::limit(strip_tags($post['content']), 150),
                'tags' => ['rezerwist', 'sistem', Str::slug($post['category']), 'yönetim'],
            ]);
        }
    }
}
