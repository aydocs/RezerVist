@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8" x-data="{ 
    activeTab: '{{ session('active_tab', 'general') }}',
    confirmSave() {
        Swal.fire({
            title: '<span class=\'text-2xl font-black tracking-tight\'>Ayarları Kaydet?</span>',
            html: '<p class=\'text-slate-500 font-medium\'>Değişiklikler platform genelinde hemen aktif olacaktır.</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#7C3AED',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Evet, Uygula',
            cancelButtonText: 'Vazgeç',
            customClass: {
                confirmButton: 'rounded-2xl px-8 py-4 font-bold',
                cancelButton: 'rounded-2xl px-8 py-4 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('settings-form').submit();
            }
        });
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-3xl shadow-sm border border-slate-200/60">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Sistem Ayarları</h1>
                <p class="text-slate-500 font-medium">Platformun temel yapılandırmasını yönetin (18 Parça).</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-6 py-3 bg-slate-50 text-slate-700 font-bold rounded-2xl hover:bg-slate-100 transition-all border border-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Geri Dön
            </a>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center gap-4 text-emerald-800 font-bold">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-12 gap-8">
            <!-- Sidebar Nav -->
            <div class="col-span-12 lg:col-span-3">
                <nav class="space-y-2 p-2 bg-white rounded-3xl border border-slate-200/60 shadow-sm">
                    <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-purple-600 text-white shadow-lg shadow-purple-200' : 'text-slate-500 hover:bg-slate-50'" class="w-full flex items-center gap-4 px-5 py-4 rounded-2xl font-bold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span>Genel</span>
                    </button>
                    <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-purple-600 text-white shadow-lg shadow-purple-200' : 'text-slate-500 hover:bg-slate-50'" class="w-full flex items-center gap-4 px-5 py-4 rounded-2xl font-bold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span>SEO</span>
                    </button>
                    <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'bg-purple-600 text-white shadow-lg shadow-purple-200' : 'text-slate-500 hover:bg-slate-50'" class="w-full flex items-center gap-4 px-5 py-4 rounded-2xl font-bold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>İletişim</span>
                    </button>
                    <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'bg-purple-600 text-white shadow-lg shadow-purple-200' : 'text-slate-500 hover:bg-slate-50'" class="w-full flex items-center gap-4 px-5 py-4 rounded-2xl font-bold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Sosyal Medya</span>
                    </button>
                    <button @click="activeTab = 'system'" :class="activeTab === 'system' ? 'bg-purple-600 text-white shadow-lg shadow-purple-200' : 'text-slate-500 hover:bg-slate-50'" class="w-full flex items-center gap-4 px-5 py-4 rounded-2xl font-bold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        <span>Sistem</span>
                    </button>
                </nav>
            </div>

            <!-- Content -->
            <div class="col-span-12 lg:col-span-9">
                <form action="{{ route('admin.settings.update') }}" method="POST" id="settings-form" class="space-y-8">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="active_tab" :value="activeTab">

                    <!-- General -->
                    <div x-show="activeTab === 'general'" class="bg-white rounded-3xl p-8 border border-slate-200/60 shadow-sm space-y-6">
                        <h2 class="text-xl font-bold text-slate-800 border-b pb-4">Genel Ayarlar</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Site Adı</label>
                                <input type="text" name="site_name" value="{{ $settings->get('general')?->where('key', 'site_name')->first()?->value }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-bold">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Site Sloganı</label>
                                <input type="text" name="site_tagline" value="{{ $settings->get('general')?->where('key', 'site_tagline')->first()?->value }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-bold">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Copyright Metni</label>
                                <input type="text" name="site_copyright" value="{{ $settings->get('general')?->where('key', 'site_copyright')->first()?->value }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-bold">
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div x-show="activeTab === 'seo'" class="bg-white rounded-3xl p-8 border border-slate-200/60 shadow-sm space-y-6">
                        <h2 class="text-xl font-bold text-slate-800 border-b pb-4">SEO Bilgileri</h2>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Meta Başlık</label>
                                <input type="text" name="seo_title" value="{{ $settings->get('seo')?->where('key', 'seo_title')->first()?->value }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-bold">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Meta Açıklama</label>
                                <textarea name="seo_description" rows="3" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-bold resize-none">{{ $settings->get('seo')?->where('key', 'seo_description')->first()?->value }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Anahtar Kelimeler</label>
                                <textarea name="seo_keywords" rows="2" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-bold resize-none">{{ $settings->get('seo')?->where('key', 'seo_keywords')->first()?->value }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div x-show="activeTab === 'contact'" class="bg-white rounded-3xl p-8 border border-slate-200/60 shadow-sm space-y-6">
                        <h2 class="text-xl font-bold text-slate-800 border-b pb-4">İletişim Bilgileri</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">E-posta</label>
                                <input type="email" name="contact_email" value="{{ $settings->get('contact')?->where('key', 'contact_email')->first()?->value }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-bold">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Telefon</label>
                                <input type="text" name="contact_phone" value="{{ $settings->get('contact')?->where('key', 'contact_phone')->first()?->value }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-bold">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Adres</label>
                                <textarea name="contact_address" rows="3" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-bold resize-none">{{ $settings->get('contact')?->where('key', 'contact_address')->first()?->value }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Harita Iframe Kodu</label>
                                <textarea name="contact_map_iframe" rows="4" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-mono text-sm resize-none">{{ $settings->get('contact')?->where('key', 'contact_map_iframe')->first()?->value }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Social -->
                    <div x-show="activeTab === 'social'" class="bg-white rounded-3xl p-8 border border-slate-200/60 shadow-sm space-y-6">
                        <h2 class="text-xl font-bold text-slate-800 border-b pb-4">Sosyal Kanallar</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach(['facebook', 'instagram', 'twitter', 'whatsapp', 'app_store', 'play_store'] as $social)
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">{{ ucfirst($social) }} Link</label>
                                <input type="text" name="social_{{ $social }}" value="{{ $settings->get('social')?->where('key', 'social_' . $social)->first()?->value }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-bold">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- System -->
                    <div x-show="activeTab === 'system'" class="bg-white rounded-3xl p-8 border border-slate-200/60 shadow-sm space-y-8">
                        <h2 class="text-xl font-bold text-slate-800 border-b pb-4">Sistem Yapılandırması</h2>
                        
                        <div class="flex items-center justify-between p-6 bg-slate-900 rounded-2xl text-white">
                            <div>
                                <h4 class="text-lg font-black">Bakım Modu</h4>
                                <p class="text-slate-400 text-sm">Siteyi ziyaretçilere kapat.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer scale-110">
                                <input type="hidden" name="system_maintenance_present" value="1">
                                <input type="checkbox" name="system_maintenance" value="1" class="sr-only peer" {{ ($settings->get('system')?->where('key', 'system_maintenance')->first()?->value ?? 0) == 1 ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-500"></div>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Zaman Dilimi</label>
                            <select name="system_timezone" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-bold">
                                @foreach($timezones as $tz)
                                <option value="{{ $tz['id'] }}" {{ ($settings->get('system')?->where('key', 'system_timezone')->first()?->value ?? 'Europe/Istanbul') == $tz['id'] ? 'selected' : '' }}>
                                    {{ $tz['name'] }} ({{ $tz['offset'] }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Save -->
                    <div class="pt-6">
                        <button type="button" @click="confirmSave()" class="w-full md:w-auto px-12 py-5 bg-purple-600 text-white font-black rounded-2xl hover:bg-purple-700 hover:shadow-xl hover:shadow-purple-500/20 transition-all transform hover:-translate-y-1 active:translate-y-0 text-lg">
                            Ayarları Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection