@extends('layouts.app')

@section('title', 'Arkadaşını Davet Et - Rezervist')

@section('content')
@php
    $user = auth()->user();
@endphp

<div class="bg-gray-50/50 min-h-screen py-12 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3">
                <div class="sticky top-24">
                    @include('profile.partials.sidebar')
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-9">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Arkadaşını Davet Et</h1>
                    <p class="text-gray-500 mt-2">Arkadaşlarınızı davet edin, hem siz kazanın hem onlar kazansın.</p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Total Invited -->
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xl shadow-gray-100/50 flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Davet Edilen</p>
                            <h2 class="text-3xl font-black text-gray-900">{{ $referralCount }} <span class="text-sm font-medium text-gray-400">Kişi</span></h2>
                        </div>
                    </div>

                    <!-- Total Earnings -->
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xl shadow-gray-100/50 flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Toplam Kazanç</p>
                            <h2 class="text-3xl font-black text-gray-900">₺{{ number_format($totalEarnings, 2) }}</h2>
                        </div>
                    </div>
                </div>

                <!-- Referral Code Card -->
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-8 text-white shadow-2xl shadow-indigo-500/30 mb-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-black/10 rounded-full -ml-10 -mb-10 blur-xl"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-2xl font-black mb-2">Sana Özel Davet Kodun</h3>
                            <p class="text-indigo-100 mb-6 max-w-md">Bu kodu arkadaşlarınla paylaş. Onlar kayıt olurken bu kodu girdiklerinde ve ilk rezervasyonlarını tamamladıklarında kazanmaya başla!</p>
                            
                            <div class="flex items-center gap-3 justify-center md:justify-start">
                                <span class="bg-white/20 backdrop-blur-md border border-white/30 px-6 py-3 rounded-xl font-mono text-2xl tracking-widest font-bold select-all" id="referralCode">{{ $user->referral_code }}</span>
                                <button onclick="copyCode()" class="bg-white text-indigo-700 px-6 py-3 rounded-xl font-bold hover:bg-indigo-50 transition shadow-lg flex items-center gap-2 group">
                                    <i class="fa-regular fa-copy group-hover:scale-110 transition-transform"></i>
                                    <span>Kopyala</span>
                                </button>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center border-4 border-white/20">
                                <i class="fa-solid fa-gift text-5xl animate-bounce"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Referred Users List -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-100/50 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Davet Ettiklerim</h3>
                        {{ $referredUsers->links() }}
                    </div>
                    
                    @if($referredUsers->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($referredUsers as $referred)
                                <div class="px-8 py-5 flex items-center justify-between hover:bg-gray-50 transition group">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <img src="{{ $referred->profile_photo_url }}" alt="{{ $referred->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm group-hover:border-indigo-100 transition">
                                            @if($referred->email_verified_at)
                                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white flex items-center justify-center text-white text-[10px]">
                                                    <i class="fa-solid fa-check"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 group-hover:text-primary transition">{{ $referred->name }}</h4>
                                            <p class="text-xs text-gray-500 font-medium">Kayıt: {{ $referred->created_at->translatedFormat('d F Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                            <i class="fa-solid fa-user-check mr-1.5"></i>
                                            Üye Oldu
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                                <i class="fa-solid fa-gift text-4xl text-indigo-400"></i>
                            </div>
                            <h3 class="text-xl font-black text-gray-900 mb-2">Henüz Kimseyi Davet Etmediniz</h3>
                            <p class="text-gray-500 max-w-sm mx-auto mb-8 leading-relaxed">Arkadaşlarınıza özel kodunuzu gönderin, onlar üye olup ilk rezervasyonlarını yaptıklarında 50 TL kazanın.</p>
                            <button onclick="copyCode()" class="px-8 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-bold transition flex items-center gap-2 transform active:scale-95 shadow-lg shadow-gray-900/20">
                                <i class="fa-regular fa-copy"></i>
                                <span>Kodu Kopyala</span>
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function copyCode() {
        var codeElement = document.getElementById("referralCode");
        var codeText = codeElement.innerText.trim();
        
        if (!codeText) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Kopyalanacak kod bulunamadı',
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }

        navigator.clipboard.writeText(codeText).then(function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Kod kopyalandı!',
                showConfirmButton: false,
                timer: 2000
            });
        }).catch(function(err) {
            console.error('Copy failed:', err);
            // Fallback for older browsers or non-secure contexts
            var textArea = document.createElement("textarea");
            textArea.value = codeText;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Kod kopyalandı!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } catch (err) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Kopyalama başarısız',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
            document.body.removeChild(textArea);
        });
    }
</script>
@endsection
