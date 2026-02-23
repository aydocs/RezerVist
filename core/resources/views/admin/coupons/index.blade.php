@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1700px] mx-auto space-y-8">
        
        <!-- Header & Top Nav -->
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b pb-8 border-slate-200">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Yönetim</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Kampanya Operasyonları</span>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Kupon Yönetimi</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Promosyon <span class="text-purple-600">Dizini</span></h1>
            </div>

            <div class="flex items-center gap-3 mt-4 md:mt-0">
                <a href="{{ route('admin.coupons.create') }}" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black rounded-2xl hover:bg-purple-600 transition-all uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-slate-900/10 active:scale-95">
                    <i class="fa-solid fa-plus-circle text-xs opacity-50"></i>
                    Yeni Kupon Tanımla
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-3 px-6 py-3 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-2xl border border-emerald-100 w-fit animate-in fade-in slide-in-from-top-4">
                <i class="fa-solid fa-circle-check text-xs"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Data Optimization Grid -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Kupon & Segment</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Finansal Model</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Kullanım Verisi</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Vade Durumu</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Statü</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Düzenleme</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($coupons as $coupon)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-4">
                                <div class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white group-hover:border-slate-900 transition-all duration-300">
                                    <i class="fa-solid fa-ticket text-[11px]"></i>
                                </div>
                                <div>
                                    <div class="text-[11px] font-black text-slate-900 tracking-tight font-mono uppercase">{{ $coupon->code }}</div>
                                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Oluşturuldu: {{ $coupon->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                            @if($coupon->type === 'percentage')
                                <div class="inline-flex items-center px-2 py-0.5 bg-purple-50 text-purple-600 rounded text-[9px] font-black border border-purple-100 uppercase tracking-widest">%{{ number_format($coupon->value, 0) }} İNDİRİM</div>
                            @else
                                <div class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[9px] font-black border border-emerald-100 uppercase tracking-widest">{{ number_format($coupon->value, 2) }} ₺ SABİT</div>
                            @endif
                            <div class="text-[8px] font-bold text-slate-400 mt-1 uppercase tracking-widest">MİN SEPET: {{ number_format($coupon->min_amount, 2) }} ₺</div>
                        </td>
                        <td class="px-8 py-4">
                            <div class="flex flex-col items-center">
                                @php
                                    $percentage = $coupon->max_uses ? ($coupon->used_count / $coupon->max_uses) * 100 : 0;
                                @endphp
                                <div class="text-[10px] font-black text-slate-700 mb-1.5 whitespace-nowrap">{{ $coupon->used_count }}{{ $coupon->max_uses ? ' / ' . $coupon->max_uses : ' (SINIRSIZ)' }}</div>
                                <div class="w-20 h-1 bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                                    <div class="h-full {{ $percentage > 90 ? 'bg-rose-500' : 'bg-purple-600' }} transition-all duration-700" style="width: {{ min(100, $percentage) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                            @if($coupon->expires_at)
                                <div class="text-[10px] font-black {{ $coupon->expires_at->isPast() ? 'text-rose-500' : 'text-slate-900' }} uppercase tracking-widest">{{ $coupon->expires_at->format('d/m/Y') }}</div>
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $coupon->expires_at->diffForHumans() }}</div>
                            @else
                                <span class="text-[9px] font-black text-slate-300 italic uppercase tracking-[0.2em] select-none">ÖMÜRLÜK</span>
                            @endif
                        </td>
                        <td class="px-8 py-4 text-center">
                            <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST">
                                @csrf
                                <button type="submit" class="group/toggle relative inline-flex h-4 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $coupon->is_active ? 'bg-purple-600 shadow-sm shadow-purple-600/20' : 'bg-slate-200' }}">
                                    <span class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out {{ $coupon->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </form>
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all" title="Konfigürasyon">
                                    <i class="fa-solid fa-sliders text-[11px]"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" onsubmit="return confirm('Promosyon kuponu sistemden kaldırılacaktır. Onaylıyor musunuz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all rounded-lg" title="Kuponu Sil">
                                        <i class="fa-solid fa-trash-arrow-up text-[11px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-32 text-center opacity-20">
                            <i class="fa-solid fa-ticket-slash text-5xl mb-4"></i>
                            <p class="text-[9px] font-black uppercase tracking-[0.2em]">Aktif kampanya bulunmuyor</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
        <div class="flex justify-center mt-6">
            {{ $coupons->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.pagination { @apply flex items-center justify-center gap-2; }
.page-item .page-link { 
    @apply w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-400 text-[10px] font-black transition-all hover:bg-slate-50 hover:text-slate-900 shadow-sm;
}
.page-item.active .page-link {
    @apply bg-purple-600 border-purple-600 text-white shadow-lg shadow-purple-600/20;
}
.page-item.disabled .page-link { @apply opacity-50 cursor-not-allowed bg-slate-50; }
</style>
@endsection
