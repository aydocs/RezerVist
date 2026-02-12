@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-20 font-sans overflow-x-hidden" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        
        <!-- Header Section -->
        <div class="text-center mb-16 transition-all duration-700 transform" 
             :class="show ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
            <div class="inline-flex items-center px-4 py-1.5 mb-6 bg-primary/5 rounded-full border border-primary/10">
                <span class="text-xs font-bold text-primary tracking-widest uppercase">Başvuru Takip Paneli</span>
            </div>
            <h1 class="text-5xl font-black text-slate-900 mb-4 tracking-tight">Başvuru Durumu</h1>
            <p class="text-lg text-slate-500 max-w-lg mx-auto leading-relaxed">Başvurunuzun güncel aşamasını ve detaylarını aşağıdan takip edebilirsiniz.</p>
        </div>

        <!-- Progress Tracker Card -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 p-10 mb-10 border border-slate-100 relative overflow-hidden transition-all duration-700 delay-200 transform"
             :class="show ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
            
            <!-- Background Decorative Elements -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl"></div>

            <div class="relative">
                <!-- Progress Line Background -->
                <div class="absolute top-6 left-6 right-6 h-1 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-primary to-indigo-500 transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(124,58,237,0.3)]" 
                         style="width: {{ $application->status == 'pending' ? '50%' : ($application->status == 'under_review' ? '75%' : '100%') }}"></div>
                </div>

                <!-- Steps Container -->
                <div class="relative flex justify-between items-start">
                    <!-- Step 1: Received -->
                    <div class="flex flex-col items-start w-1/3">
                        <div class="w-12 h-12 rounded-2xl bg-primary shadow-lg shadow-primary/30 flex items-center justify-center relative z-10 transition-transform hover:scale-110">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="mt-4 pr-4">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wide">Başvuru Alındı</h3>
                            <p class="text-xs text-slate-400 font-bold mt-1">{{ $application->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Step 2: Under Review -->
                    <div class="flex flex-col items-center w-1/3 text-center">
                        <div class="w-12 h-12 rounded-2xl {{ $application->status != 'pending' ? 'bg-primary shadow-lg shadow-primary/30' : 'bg-white border-2 border-slate-100' }} flex items-center justify-center relative z-10 transition-transform hover:scale-110">
                            @if($application->status == 'under_review')
                                <div class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                                </div>
                            @elseif($application->status == 'approved' || $application->status == 'rejected')
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            @else
                                <svg class="w-6 h-6 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>
                        <div class="mt-4">
                            <h3 class="text-sm font-black {{ $application->status != 'pending' ? 'text-slate-900' : 'text-slate-300' }} uppercase tracking-wide">İncelemede</h3>
                            <p class="text-[10px] {{ $application->status == 'under_review' ? 'text-primary' : 'text-slate-400' }} font-bold mt-1 uppercase">
                                {{ $application->status == 'under_review' ? 'Kontrol Ediliyor' : ($application->status == 'pending' ? 'Sırada Bekliyor' : 'Kontrol Edildi') }}
                            </p>
                        </div>
                    </div>

                    <!-- Step 3: Decision -->
                    <div class="flex flex-col items-end w-1/3 text-right">
                        <div class="w-12 h-12 rounded-2xl 
                            {{ $application->status == 'approved' ? 'bg-green-500 shadow-lg shadow-green-200' : ($application->status == 'rejected' ? 'bg-red-500 shadow-lg shadow-red-200' : 'bg-white border-2 border-slate-100') }} 
                            flex items-center justify-center relative z-10 transition-transform hover:scale-110">
                            @if($application->status == 'approved')
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            @elseif($application->status == 'rejected')
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                            @else
                                <svg class="w-6 h-6 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            @endif
                        </div>
                        <div class="mt-4 pl-4">
                            <h3 class="text-sm font-black 
                                {{ $application->status == 'approved' ? 'text-green-600' : ($application->status == 'rejected' ? 'text-red-600' : 'text-slate-300') }} uppercase tracking-wide">
                                {{ $application->status == 'approved' ? 'Onaylandı' : ($application->status == 'rejected' ? 'Reddedildi' : 'Sonuç') }}
                            </h3>
                            <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase">Nihai Karar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 transition-all duration-700 delay-400 transform"
             :class="show ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
            
            <!-- Main Content (2 cols) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Summary Card -->
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                        <h2 class="text-xl font-black text-slate-900">Başvuru Özeti</h2>
                        @if($application->status == 'pending' || $application->status == 'rejected' || $application->status == 'under_review')
                            <a href="{{ route('business.application.edit') }}" class="group flex items-center gap-2 px-4 py-2 bg-primary text-white text-xs font-black rounded-xl hover:bg-primary/90 transition shadow-lg shadow-primary/20 transform hover:-translate-y-0.5 active:translate-y-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                BİLGİLERİ DÜZENLE
                            </a>
                        @endif
                    </div>

                    <div class="p-8">
                        @if($application->status == 'rejected' && $application->admin_note)
                            <div class="bg-red-50 rounded-2xl p-6 border border-red-100 mb-8 animate-pulse-slow">
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0 text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-red-900 font-black text-sm uppercase tracking-wide">Yönetici Notu</h4>
                                        <p class="text-red-700 text-sm mt-1 leading-relaxed">{{ $application->admin_note }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-10 gap-x-8">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">İşletme Adı</label>
                                <p class="text-slate-900 font-bold text-lg leading-tight">{{ $application->business_name }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Kategori</label>
                                <p class="text-slate-900 font-bold text-lg leading-tight">{{ $application->category->name ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">E-posta</label>
                                <p class="text-slate-600 font-medium truncate">{{ $application->email }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">Telefon</label>
                                <p class="text-slate-600 font-medium">{{ $application->phone }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 block">İşletme Adresi</label>
                                <p class="text-slate-600 font-medium leading-relaxed">{{ $application->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Card -->
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                    <h3 class="text-xl font-black text-slate-900 mb-6">Yüklenen Belgeler</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach(['trade_registry_document' => 'Ticaret Sicili', 'tax_document' => 'Vergi Levhası', 'license_document' => 'İşletme Ruhsatı', 'id_document' => 'Kimlik Belgesi', 'bank_document' => 'Banka Bilgileri'] as $key => $label)
                            <div class="flex items-center p-4 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-primary/20 transition group">
                                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-black text-slate-900 uppercase tracking-wide">{{ $label }}</span>
                                    <p class="text-[10px] text-slate-400 font-bold mt-0.5">YÜKLENDİ</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar (1 col) -->
            <div class="space-y-8">
                <!-- Sidebar Status Badge -->
                <div class="bg-slate-900 rounded-[2rem] p-10 text-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-indigo-500/30 opacity-50"></div>
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center mx-auto mb-6 border border-white/20 transition-transform group-hover:scale-110">
                            @if($application->status == 'pending' || $application->status == 'under_review')
                                <svg class="w-10 h-10 text-white animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            @elseif($application->status == 'approved')
                                <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @elseif($application->status == 'rejected')
                                <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>
                        <h4 class="text-white/60 text-[10px] font-black uppercase tracking-[0.2em] mb-2">GÜNCEL DURUM</h4>
                        <p class="text-3xl font-black text-white leading-tight">
                            {{ $application->status == 'under_review' ? 'İncelemede' : ($application->status == 'pending' ? 'Beklemede' : ($application->status == 'approved' ? 'Onaylandı' : 'Reddedildi')) }}
                        </p>
                    </div>
                </div>

                <!-- Process Info Box -->
                <div class="bg-indigo-600 rounded-[2rem] p-10 text-white shadow-xl shadow-indigo-100 flex flex-col gap-6">
                    <h3 class="font-black text-xl flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        Süreç Nasıl İlerler?
                    </h3>
                    <ul class="space-y-6 text-sm font-bold text-indigo-100 leading-relaxed">
                        <li class="flex gap-4">
                            <span class="flex-shrink-0 w-7 h-7 bg-white/10 rounded-lg flex items-center justify-center border border-white/20 text-xs">1</span>
                            <span class="pt-0.5">Başvurunuzu inceliyoruz (Genellikle 24 saat içinde yanıtlanır).</span>
                        </li>
                        <li class="flex gap-4">
                            <span class="flex-shrink-0 w-7 h-7 bg-white/10 rounded-lg flex items-center justify-center border border-white/20 text-xs">2</span>
                            <span class="pt-0.5">Gerekli belgelerin geçerliliğini ve doğruluğunu teyit ediyoruz.</span>
                        </li>
                        <li class="flex gap-4">
                            <span class="flex-shrink-0 w-7 h-7 bg-white/10 rounded-lg flex items-center justify-center border border-white/20 text-xs">3</span>
                            <span class="pt-0.5">Başvurunuz onaylandığında giriş bilgileriniz e-posta ile iletilir.</span>
                        </li>
                    </ul>
                </div>

                <!-- Support Link -->
                <div class="text-center">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-3">YARDIM MI LAZIM?</p>
                    <a href="{{ route('pages.contact') }}" class="inline-flex items-center gap-2 text-primary font-black text-sm hover:underline">
                        Müşteri Hizmetlerine Yazın
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }
    .animate-pulse-slow {
        animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
</style>
@endsection
