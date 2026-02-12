<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function rezervistaPos()
    {
        try {
            // Bypass Blade Engine - Serve Plain PHP
            // Note: 'pages.pos-plain' will look for pos-plain.blade.php OR pos-plain.php
            return view('pages.pos-plain');
        } catch (\Throwable $e) {
            \Log::error('POS Rendering Error: '.$e->getMessage());

            return response('<h1>Kritik Hata</h1><p>'.$e->getMessage().'</p>', 500);
        }
    }

    public function business()
    {
        return view('pages.business');
    }

    public function help()
    {
        $faqs = [
            ['q' => 'Rezervasyon yapmak ücretli mi?', 'a' => 'Hayır, Rezervist üzerinden yapacağınız tüm rezervasyonlar tamamen ücretsizdir.'],
            ['q' => 'Rezervasyonumu iptal edebilir miyim?', 'a' => 'Evet, rezervasyon saatinizden 1 saat öncesine kadar profilinizden iptal işlemi gerçekleştirebilirsiniz.'],
            ['q' => 'İşletme hesabımı nasıl oluşturabilirim?', 'a' => 'İşletme Ortağımız Olun sayfasındaki başvuru formunu doldurarak sürecinizi başlatabilirsiniz.'],
            ['q' => 'Rezervasyon onayımı nasıl görebilirim?', 'a' => 'Rezervasyonunuz onaylandığında size hem e-posta gönderilir hem de Profil -> Rezervasyonlarım sekmesinden durumunu takip edebilirsiniz.'],
            ['q' => 'Ödeme yapmak güvenli mi?', 'a' => 'Evet, tüm ödeme işlemleri 256-bit SSL sertifikalı güvenli altyapımız ve BDDK lisanslı ödeme kuruluşları üzerinden gerçekleştirilmektedir.'],
            ['q' => 'Şifremi unuttum, ne yapmalıyım?', 'a' => 'Giriş sayfasındaki "Şifremi Unuttum" bağlantısına tıklayarak kayıtlı e-posta adresinize yeni şifre oluşturma bağlantısı isteyebilirsiniz.'],
        ];

        return view('pages.help', compact('faqs'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(\App\Http\Requests\StoreContactRequest $request)
    {
        $validated = $request->validated();

        // 1. Send Internal Notification to Admin
        try {
            \Illuminate\Support\Facades\Mail::send('emails.admin-contact-notification', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'contact_message' => $validated['message'],
            ], function ($mail) use ($validated) {
                $mail->to('admin@rezervist.com')
                    ->subject('🔔 Yeni İletişim Formu Mesajı: '.$validated['subject']);
            });

            // 2. Send Receipt to User
            \Illuminate\Support\Facades\Mail::send('emails.user-contact-receipt', [
                'name' => $validated['name'],
                'contact_message' => $validated['message'],
            ], function ($mail) use ($validated) {
                $mail->to($validated['email'])
                    ->subject('Mesajınızı Aldık - Rezervist');
            });
        } catch (\Exception $e) {
            \Log::error('Contact form mail error: '.$e->getMessage());
        }

        // 2. Log Activity (As requested: "platform activity sayfasına ekle")
        \App\Models\ActivityLog::logActivity(
            'contact_form_mail',
            "İletişim Formu Mesajı: {$validated['subject']}",
            [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ],
            auth()->id() // Log user ID if logged in, otherwise null
        );

        // 3. User Notification (As requested: "bildirimlere ekle")
        if (auth()->check()) {
            auth()->user()->notify(new \App\Notifications\SystemNotification(
                'Mesajınız İletildi',
                'İletişim formundan gönderdiğiniz mesaj tarafımıza ulaşmıştır.',
                'check-circle',
                'system'
            ));
        }

        return back()->with('success', 'Mesajınız başarıyla iletildi. En kısa sürede e-posta yoluyla dönüş yapılacaktır.');
    }

    public function liveSupport()
    {
        return view('pages.live-support');
    }

    public function submitLiveSupport(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $contactMessage = \App\Models\ContactMessage::create([
            'user_id' => auth()->id(),
            'name' => auth()->user() ? auth()->user()->name : 'Ziyaretçi',
            'email' => auth()->user() ? auth()->user()->email : 'destek@rezervist.com',
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        // Log Activity
        \App\Models\ActivityLog::logActivity(
            'support_ticket_created',
            "Yeni Destek Talebi: {$validated['subject']}",
            ['ticket_id' => $contactMessage->id]
        );

        // User Notification
        if (auth()->check()) {
            auth()->user()->notify(new \App\Notifications\SystemNotification(
                'Destek Talebi Oluşturuldu',
                'Talebiniz başarıyla alındı. Destek ekibimiz en kısa sürede yanıt verecektir.',
                'clock',
                'support',
                route('pages.live-support') // Or link to specific ticket if we had a detail view
            ));
        }

        return back()->with('success', 'Destek talebiniz oluşturuldu. Yetkililerimiz en kısa sürede yanıt verecektir.');
    }

    public function campaigns()
    {
        // Fetch active coupons from database
        $campaigns = \App\Models\Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->latest()
            ->get();

        return view('pages.campaigns', compact('campaigns'));
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function contracts()
    {
        return view('pages.contracts');
    }

    public function docs()
    {
        return view('pages.docs');
    }

    public function about()
    {
        // Cache stats for 1 hour to improve performance
        $stats = \Illuminate\Support\Facades\Cache::remember('about_page_stats', 3600, function () {
            return [
                'businesses' => \App\Models\Business::where('is_active', true)->count(),
                'monthly_reservations' => \App\Models\Reservation::whereMonth('created_at', now()->month)->count(),
                'customers' => \App\Models\User::where('role', 'customer')->count(),
                'cities' => \App\Models\Location::whereHas('business', function ($q) {
                    $q->where('is_active', true);
                })->distinct('city')->count('city'),
            ];
        });

        return view('pages.about', compact('stats'));
    }

    public function cookies()
    {
        return view('pages.cookies');
    }

    public function careers()
    {
        return view('pages.careers');
    }

    public function story()
    {
        return view('pages.story');
    }
}
