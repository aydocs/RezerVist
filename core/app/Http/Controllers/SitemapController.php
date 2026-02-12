<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SitemapController extends Controller
{
    public function index()
    {
        // Public Pages (Always available)
        $publicLinks = [
            ['title' => 'Anasayfa', 'url' => route('home')],
            ['title' => 'Keşfet & Ara', 'url' => route('search.index')],
            ['title' => 'İşletme Ortağımız Olun', 'url' => route('pages.business')],
            ['title' => 'RezerVist POS', 'url' => route('pages.pos')],
            ['title' => 'Kampanyalar', 'url' => route('pages.campaigns')],
            ['title' => 'Hikayemiz', 'url' => route('pages.story')],
            ['title' => 'Kariyer', 'url' => route('pages.careers')],
            ['title' => 'Yardım Merkezi', 'url' => route('pages.help')],
            ['title' => 'İletişim', 'url' => route('pages.contact')],
        ];

        // Auth Pages (For logged in users)
        $memberLinks = [];
        if (Auth::check()) {
            $memberLinks = [
                ['title' => 'Profilim', 'url' => route('profile.edit')],
                ['title' => 'Rezervasyonlarım', 'url' => route('profile.reservations')],
                ['title' => 'Favorilerim', 'url' => route('profile.favorites')],
                ['title' => 'Cüzdanım', 'url' => route('profile.wallet.index')],
                ['title' => 'Bildirimler', 'url' => route('notifications.index')],
            ];
        }

        // Business Pages (For business owners)
        $businessLinks = [];
        if (Auth::check() && (Auth::user()->role === 'business' || Auth::user()->role === 'admin')) {
            $businessLinks = [
                ['title' => 'İşletme Paneli', 'url' => route('vendor.dashboard')],
                ['title' => 'Rezervasyon Yönetimi', 'url' => route('vendor.reservations.index')],
                ['title' => 'Menü ve Hizmetler', 'url' => route('vendor.menus.index')],
                ['title' => 'Masa & Alan Yönetimi', 'url' => route('vendor.resources.index')],
                ['title' => 'Personel Yönetimi', 'url' => route('vendor.staff.index')],
                ['title' => 'Müşteri Veritabanı', 'url' => route('vendor.customers')],
                ['title' => 'Finansal Raporlar', 'url' => route('vendor.finance.index')],
                ['title' => 'Şube Ayarları', 'url' => route('vendor.business.edit')],
            ];
        }

        // Admin Pages (For admins)
        $adminLinks = [];
        if (Auth::check() && Auth::user()->role === 'admin') {
            $adminLinks = [
                ['title' => 'Admin Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Kullanıcı Yönetimi', 'url' => route('admin.users.index')],
                ['title' => 'İşletme Başvuruları', 'url' => route('admin.applications.index')],
                ['title' => 'Sistem Sağlığı', 'url' => route('admin.health')],
                ['title' => 'Raporlar', 'url' => route('admin.reports.index')],
                ['title' => 'Genel Ayarlar', 'url' => route('admin.settings.index')],
                ['title' => 'Kupon Yönetimi', 'url' => route('admin.coupons.index')],
                ['title' => 'Aktivite Logları', 'url' => route('admin.activities.system')],
            ];
        }

        // Active Businesses (For everyone)
        $activeBusinesses = \App\Models\Business::where('is_active', true)->select('id', 'name', 'slug')->get();

        return view('pages.sitemap', compact('publicLinks', 'memberLinks', 'businessLinks', 'adminLinks', 'activeBusinesses'));
    }

    public function robots()
    {
        $content = "User-agent: *\nDisallow: /admin/\nDisallow: /vendor/\nDisallow: /profile/\nAllow: /\n\nSitemap: ".route('sitemap');

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
