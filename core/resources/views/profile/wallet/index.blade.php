@extends('layouts.app')

@section('title', 'Cüzdanım - Premium Rezervasyon Deneyimi')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen py-8 lg:py-12 font-sans selection:bg-indigo-100 selection:text-indigo-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3">
                <div class="sticky top-24 space-y-6">
                    @include('profile.partials.sidebar')
                    
                    <!-- Quick Safety Tip -->
                    <div class="bg-indigo-600 rounded-3xl p-6 text-white shadow-xl shadow-indigo-200 overflow-hidden relative group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                        <h3 class="font-bold text-sm mb-2 relative z-10">Güvenli Ödeme</h3>
                        <p class="text-xs text-indigo-100 leading-relaxed relative z-10">Tüm işlemleriniz 256-bit SSL ve iyzico güvencesi ile korunmaktadır.</p>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-9 space-y-8">
                
                <!-- Status Messages -->
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 text-emerald-700">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="font-semibold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300" class="bg-rose-50 border border-rose-100 p-4 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 text-rose-700">
                        <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <span class="font-semibold text-sm">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                @endif

                <!-- Hero Section: Virtual Card + Stats -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <!-- The Card -->
                    <div class="relative group h-[280px]">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-700 via-indigo-900 to-slate-900 rounded-[2.5rem] shadow-2xl shadow-indigo-900/40 transform group-hover:-rotate-1 transition-transform duration-500 overflow-hidden border border-white/10">
                            <!-- Background Textures -->
                            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                            <div class="absolute -right-20 -top-20 w-64 h-64 bg-primary/20 rounded-full blur-3xl animate-pulse"></div>
                            
                            <!-- Card Content -->
                            <div class="relative p-10 h-full flex flex-col justify-between">
                                <div class="flex justify-between items-start">
                                    <div class="space-y-1">
                                        <div class="text-[10px] font-black uppercase tracking-[0.4em] text-indigo-300/60">RezerVist Premium</div>
                                        <div class="text-2xl font-black text-white tracking-tighter">Virtual Card</div>
                                    </div>
                                    <div class="w-14 h-10 bg-gradient-to-br from-indigo-400/20 to-white/5 backdrop-blur-md rounded-lg border border-white/10 flex items-center justify-center overflow-hidden">
                                        <div class="w-full h-full opacity-50 flex flex-col justify-around py-1.5 transform -skew-x-12">
                                            <div class="h-px bg-white w-full"></div>
                                            <div class="h-px bg-white w-full"></div>
                                            <div class="h-px bg-white w-full"></div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="text-[10px] font-black uppercase tracking-[0.3em] text-white/40 mb-2">Mevcut Bakiye</div>
                                    <div class="flex items-baseline gap-3">
                                        <span class="text-5xl md:text-6xl font-black text-white tracking-tighter tabular-nums">₺{{ number_format($user->balance, 2, ',', '.') }}</span>
                                        <span class="text-indigo-400 font-bold text-sm tracking-widest">TRY</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-end border-t border-white/10 pt-6">
                                    <div class="flex gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500/50"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500/20"></span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[8px] font-black uppercase text-indigo-300/40 mb-1">Üye Adı</div>
                                        <div class="text-xs font-bold text-white uppercase tracking-widest">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Summary -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-between group hover:border-indigo-100 transition-colors">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Toplam Yüklenen</h4>
                                <p class="text-2xl font-black text-slate-900 tracking-tight">₺{{ number_format($totalLoaded ?? 0, 2, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-between group hover:border-indigo-100 transition-colors">
                            <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Toplam Harcanan</h4>
                                <p class="text-2xl font-black text-slate-900 tracking-tight">₺{{ number_format($totalSpent ?? 0, 2, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="col-span-2 bg-slate-900 rounded-[2rem] p-6 flex items-center justify-between group hover:bg-black transition-all cursor-pointer shadow-xl shadow-slate-900/20 border-2 border-white/5 hover:border-white/20" onclick="window.dispatchEvent(new CustomEvent('open-topup-modal'))">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/30 border-2 border-white/20 group-hover:border-white transition-all">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-black tracking-tight">Anında Bakiye Yükle</h4>
                                    <p class="text-indigo-300/50 text-xs font-bold uppercase tracking-wider">Komisyonsuz ve 7/24 hızlı yükleme</p>
                                </div>
                            </div>
                            <div class="text-white/20 group-hover:text-white transition-colors mr-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction History -->
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl shadow-slate-200/30 overflow-hidden">
                    <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 tracking-tight">İşlem Hareketleri</h2>
                            <p class="text-slate-400 text-xs mt-1">Son yaptığınız finansal hareketlerin dökümü.</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="p-2.5 rounded-xl border border-slate-100 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto px-6">
                        <table class="w-full">
                            <tbody class="divide-y divide-slate-50">
                                @forelse($transactions as $tx)
                                <tr class="group hover:bg-slate-50/80 transition-all rounded-3xl">
                                    <td class="px-4 py-8">
                                        <div class="flex items-center gap-6">
                                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 transition-all duration-300
                                                {{ in_array($tx->type, ['topup', 'earning']) ? 'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white' : 'bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white' }}">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if(in_array($tx->type, ['topup', 'earning']))
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    @endif
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <p class="text-sm font-black text-slate-800 tracking-tight">{{ $tx->description }}</p>
                                                    @if($tx->status == 'success')
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                                                    @endif
                                                </div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $tx->created_at->translatedFormat('d F Y • H:i') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-8 text-right">
                                        <div class="flex flex-col items-end">
                                            <span class="text-lg font-black tracking-tighter tabular-nums {{ ($tx->amount > 0 && in_array($tx->type, ['topup', 'earning'])) ? 'text-emerald-600' : 'text-slate-900' }}">
                                                {{ ($tx->amount > 0 && in_array($tx->type, ['topup', 'earning'])) ? '+' : '-' }}₺{{ number_format(abs($tx->amount), 2, ',', '.') }}
                                            </span>
                                            <span class="text-[9px] font-black uppercase text-slate-300 tracking-widest mt-1">İşlem No: {{ substr($tx->reference_id, 0, 8) }}...</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-10 py-20 text-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <p class="text-slate-400 font-bold tracking-tight">Henüz bir işlem hareketi bulunamadı.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($transactions->hasPages())
                    <div class="px-10 py-8 bg-slate-50/50 border-t border-slate-50">
                        {{ $transactions->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Premium Top-up Modal -->
<div x-data="{ 
    open: false, 
    amount: 100, 
    loading: false,
    startPayment() {
        if(this.amount < 10) { alert('Minimum 10 TL yükleyebilirsiniz.'); return; }
        this.loading = true;
        fetch('{{ route('wallet.topup') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ amount: this.amount })
        })
        .then(async res => {
            if (!res.ok) {
                const text = await res.text();
                throw new Error('Server Error: ' + res.status);
            }
            return res.json();
        })
        .then(data => {
            if(data.status === 'success') {
                window.location.href = data.url;
            } else {
                alert('API Error: ' + data.message);
                this.loading = false;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Ödeme başlatılamadı, lütfen tekrar deneyin.');
            this.loading = false;
        });
    }
}" 
     @open-topup-modal.window="open = true"
     x-show="open" 
     class="fixed inset-0 z-[100] overflow-y-auto" 
     style="display: none;">
    
    <div x-show="open" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-md" @click="!loading && (open = false)"></div>

    <div class="flex items-center justify-center min-h-screen px-4 py-12">
        <div x-show="open" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             class="bg-white rounded-[3rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] max-w-lg w-full relative z-[101] overflow-hidden">
            
            <div class="h-3 bg-gradient-to-r from-primary via-indigo-600 to-indigo-900"></div>
            
            <div class="p-10 md:p-12">
                <div class="flex justify-between items-start mb-10">
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Bakiye Yükle</h3>
                        <p class="text-slate-400 text-sm font-medium">Lütfen yüklemek istediğiniz tutarı seçin.</p>
                    </div>
                    <button @click="open = false" class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="space-y-8">
                    <div class="relative group">
                        <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-3 ml-1">Ödeme Tutarı</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-3xl font-black text-indigo-200 group-focus-within:text-primary transition-colors">₺</span>
                            <input type="number" x-model.number="amount" class="w-full pl-14 pr-8 py-7 rounded-[2rem] border-2 border-slate-100 focus:border-primary focus:ring-0 text-4xl font-black text-slate-900 bg-slate-50 transition-all outline-none" placeholder="0.00">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                         <template x-for="val in [100, 250, 500, 1000, 2500, 5000]">
                            <button @click="amount = val" 
                                    :class="amount == val ? 'bg-primary text-white shadow-lg shadow-primary/30 border-primary' : 'bg-white text-slate-600 border-slate-100 hover:border-indigo-200 hover:bg-slate-50'"
                                    class="py-4 rounded-2xl border-2 font-black transition-all text-sm group-hover:scale-105" x-text="'₺' + val">
                            </button>
                        </template>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <button @click="startPayment()" 
                            class="w-full bg-slate-900 text-white py-6 rounded-[2rem] font-black text-lg border-2 border-white/10 hover:border-white transition-all shadow-xl shadow-slate-900/20 disabled:opacity-50 flex items-center justify-center gap-4 group/btn"
                            :disabled="loading || amount < 10">
                            <template x-if="!loading">
                                <span class="flex items-center gap-3">
                                    Ödemeye Geç
                                    <svg class="w-6 h-6 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </span>
                            </template>
                            <template x-if="loading">
                                <span class="flex items-center gap-3">
                                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Lütfen Bekleyin...
                                </span>
                            </template>
                        </button>
                        
                        <div class="flex items-center justify-center gap-2 mt-6 grayscale opacity-40">
                            <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Powered by</span>
                            <img src="https://iyzico.com/assets/images/content/logo.svg?v=1" class="h-4" alt="iyzico">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
@endsection
