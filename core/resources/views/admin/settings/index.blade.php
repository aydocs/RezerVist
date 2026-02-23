@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12" x-data="{ 
    activeTab: '{{ session('active_tab', 'general') }}',
    confirmSave() {
        Swal.fire({
            title: 'Sistem Yapılandırması',
            text: 'Bu değişiklikler çekirdek sistem parametrelerini etkileyecektir.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#8B5CF6',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'UYGULA',
            cancelButtonText: 'VAZGEÇ',
            customClass: {
                title: 'text-base font-black text-slate-900 uppercase tracking-tight',
                htmlContainer: 'text-[10px] font-bold text-slate-500 uppercase tracking-widest',
                confirmButton: 'px-8 py-3 rounded-xl uppercase tracking-[0.2em] text-[10px] font-black',
                cancelButton: 'px-8 py-3 rounded-xl uppercase tracking-[0.2em] text-[10px] font-black'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('settings-form').submit();
            }
        });
    }
}">
    <div class="max-w-[1400px] mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b pb-8 border-slate-200">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Yönetim</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Yapılandırma</span>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Sistem Ayarları</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Sistem <span class="text-purple-600">Tercihleri</span></h1>
            </div>

            <div class="flex items-center gap-4">
                @if(session('success'))
                    <div class="px-6 py-2.5 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-[0.15em] rounded-xl flex items-center gap-2 animate-in fade-in slide-in-from-right-4">
                        <i class="fa-solid fa-circle-check"></i>
                        Başarıyla Kaydedildi
                    </div>
                @endif
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm flex items-center gap-2 group">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Geri Dön
                </a>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Sidebar Navigation -->
            <div class="w-full lg:w-72 flex-shrink-0">
                <div class="bg-white border border-slate-200 rounded-2xl p-2.5 space-y-1 shadow-sm sticky top-8">
                    <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-[10px] font-black uppercase tracking-widest">
                        <i class="fa-solid fa-sliders text-xs w-4"></i>
                        <span>Genel Yapı</span>
                    </button>
                    <button @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-[10px] font-black uppercase tracking-widest">
                        <i class="fa-solid fa-magnifying-glass text-xs w-4"></i>
                        <span>İndeksleme & SEO</span>
                    </button>
                    <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-[10px] font-black uppercase tracking-widest">
                        <i class="fa-solid fa-envelope text-xs w-4"></i>
                        <span>İletişim Kanalı</span>
                    </button>
                    <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-[10px] font-black uppercase tracking-widest">
                        <i class="fa-solid fa-share-nodes text-xs w-4"></i>
                        <span>Sosyal Entegrasyon</span>
                    </button>
                    <button @click="activeTab = 'system'" :class="activeTab === 'system' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-[10px] font-black uppercase tracking-widest">
                        <i class="fa-solid fa-microchip text-xs w-4"></i>
                        <span>Çekirdek Sistem</span>
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1">
                <form action="{{ route('admin.settings.update') }}" method="POST" id="settings-form" class="space-y-8">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="active_tab" :value="activeTab">

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                        <!-- General -->
                        <div x-show="activeTab === 'general'" class="p-8 space-y-8 animate-in fade-in duration-300">
                            <div class="border-b border-slate-100 pb-6">
                                <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight">Genel Konfigürasyon</h2>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Platform markalama ve temel bilgiler.</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Site Başlığı</label>
                                    <input type="text" name="site_name" value="{{ $settings->get('general')?->where('key', 'site_name')->first()?->value }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/5 transition-all outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Slogan</label>
                                    <input type="text" name="site_tagline" value="{{ $settings->get('general')?->where('key', 'site_tagline')->first()?->value }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/5 transition-all outline-none">
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Telif Hakkı (Copyright)</label>
                                    <input type="text" name="site_copyright" value="{{ $settings->get('general')?->where('key', 'site_copyright')->first()?->value }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/5 transition-all outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- SEO -->
                        <div x-show="activeTab === 'seo'" class="p-8 space-y-8 animate-in fade-in duration-300" x-cloak>
                            <div class="border-b border-slate-100 pb-6">
                                <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight">İndeksleme & SEO</h2>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Arama motoru görünürlüğü ve optimizasyon.</p>
                            </div>
                            <div class="space-y-8">
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Meta Başlık Şablonu</label>
                                    <input type="text" name="seo_title" value="{{ $settings->get('seo')?->where('key', 'seo_title')->first()?->value }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Global Meta Açıklama</label>
                                    <textarea name="seo_description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all outline-none resize-none leading-relaxed">{{ $settings->get('seo')?->where('key', 'seo_description')->first()?->value }}</textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Anahtar Kelimeler</label>
                                    <textarea name="seo_keywords" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all outline-none resize-none leading-relaxed">{{ $settings->get('seo')?->where('key', 'seo_keywords')->first()?->value }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div x-show="activeTab === 'contact'" class="p-8 space-y-8 animate-in fade-in duration-300" x-cloak>
                            <div class="border-b border-slate-100 pb-6">
                                <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight">İletişim Kanalı</h2>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Global iletişim ve lokasyon parametreleri.</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Merkezi E-posta</label>
                                    <input type="email" name="contact_email" value="{{ $settings->get('contact')?->where('key', 'contact_email')->first()?->value }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Destek Hattı</label>
                                    <input type="text" name="contact_phone" value="{{ $settings->get('contact')?->where('key', 'contact_phone')->first()?->value }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all outline-none">
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Fiziksel Adres</label>
                                    <textarea name="contact_address" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all outline-none resize-none leading-relaxed">{{ $settings->get('contact')?->where('key', 'contact_address')->first()?->value }}</textarea>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Harita Entegrasyon (Embed)</label>
                                    <textarea name="contact_map_iframe" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[10px] font-mono text-slate-600 focus:bg-white focus:border-purple-500 transition-all outline-none resize-none leading-relaxed">{{ $settings->get('contact')?->where('key', 'contact_map_iframe')->first()?->value }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Social -->
                        <div x-show="activeTab === 'social'" class="p-8 space-y-8 animate-in fade-in duration-300" x-cloak>
                            <div class="border-b border-slate-100 pb-6">
                                <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight">Sosyal Entegrasyon</h2>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Sosyal medya profilleri ve uygulama marketleri.</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                @foreach(['facebook', 'instagram', 'twitter', 'whatsapp', 'app_store', 'play_store'] as $social)
                                <div class="space-y-2 group">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within:text-purple-600 transition-colors">{{ str_replace('_', ' ', $social) }}</label>
                                    <div class="relative">
                                        @php
                                            $socialIcon = match($social) {
                                                'facebook' => 'fa-brands fa-facebook',
                                                'instagram' => 'fa-brands fa-instagram',
                                                'twitter' => 'fa-brands fa-x-twitter',
                                                'whatsapp' => 'fa-brands fa-whatsapp',
                                                'app_store' => 'fa-brands fa-apple',
                                                'play_store' => 'fa-brands fa-google-play',
                                                default => 'fa-solid fa-link'
                                            };
                                        @endphp
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm group-focus-within:text-purple-400 transition-colors">
                                            <i class="{{ $socialIcon }}"></i>
                                        </div>
                                        <input type="text" name="social_{{ $social }}" value="{{ $settings->get('social')?->where('key', 'social_' . $social)->first()?->value }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:bg-white focus:border-purple-500 transition-all outline-none" placeholder="https://...">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- System -->
                        <div x-show="activeTab === 'system'" class="p-8 space-y-10 animate-in fade-in duration-300" x-cloak>
                            <div class="border-b border-slate-100 pb-6">
                                <h2 class="text-sm font-black text-slate-900 uppercase tracking-tight">Çekirdek Sistem</h2>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Düşük seviyeli platform çalışma parametreleri.</p>
                            </div>
                            
                            <div class="flex items-center justify-between p-6 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl shadow-slate-900/10">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20">
                                        <i class="fa-solid fa-power-off text-purple-400"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-white uppercase tracking-widest">Bakım Modu (Maintenance)</h4>
                                        <p class="text-slate-400 text-[10px] font-bold mt-0.5">Aktif edildiğinde platform tüm isteklere kapalı olacaktır.</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="system_maintenance_present" value="1">
                                    <input type="checkbox" name="system_maintenance" value="1" class="sr-only peer" {{ ($settings->get('system')?->where('key', 'system_maintenance')->first()?->value ?? 0) == 1 ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500"></div>
                                </label>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Sistem Zaman Dilimi</label>
                                <div class="relative group">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm group-focus-within:text-purple-400 transition-colors pointer-events-none">
                                        <i class="fa-solid fa-clock"></i>
                                    </div>
                                    <select name="system_timezone" class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-black text-slate-700 focus:bg-white focus:border-purple-500 transition-all outline-none appearance-none cursor-pointer">
                                        @foreach($timezones as $tz)
                                        <option value="{{ $tz['id'] }}" {{ ($settings->get('system')?->where('key', 'system_timezone')->first()?->value ?? 'Europe/Istanbul') == $tz['id'] ? 'selected' : '' }}>
                                            {{ $tz['name'] }} ({{ $tz['offset'] }})
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-[10px] pointer-events-none">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="button" @click="confirmSave()" class="px-10 py-4 bg-slate-900 hover:bg-purple-600 text-white font-black rounded-xl text-[10px] uppercase tracking-[0.2em] transition-all shadow-xl shadow-slate-900/10 active:scale-95 flex items-center gap-3">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Konfigürasyonu Uygula
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection