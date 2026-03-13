@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Kupon Yönetimi</h1>
                <p class="text-sm text-gray-500 mt-1">Müşterilerinize özel kampanya ve indirim kuponları oluşturun.</p>
            </div>
            <a href="{{ route('vendor.coupons.create') }}" class="inline-flex items-center justify-center px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-[2rem] shadow-xl shadow-indigo-100 transition-all font-black text-sm gap-2 w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Yeni Kupon Oluştur
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-emerald-700 font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Coupons List -->
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            @if($coupons->count() > 0)
                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kupon Kodu</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">İndirim</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kullanım</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Min. Sepet</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Durum</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($coupons as $coupon)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                                <i class="fas fa-ticket-alt"></i>
                                            </div>
                                            <div>
                                                <span class="font-black text-slate-900 tracking-wider">{{ $coupon->code }}</span>
                                                @if($coupon->expires_at && $coupon->expires_at->isPast())
                                                    <span class="block text-[8px] font-black text-rose-500 uppercase">SÜRESİ DOLDU</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($coupon->type == 'percentage')
                                            <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase">%{{ intval($coupon->value) }} İndirim</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black uppercase">₺{{ number_format($coupon->value, 2) }} İndirim</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-slate-700">{{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}</span>
                                            @if($coupon->max_uses)
                                                <div class="w-16 h-1 bg-slate-100 rounded-full mt-1.5 overflow-hidden">
                                                    <div class="h-full bg-indigo-500 transition-all duration-500" style="width: {{ min(100, ($coupon->used_count / $coupon->max_uses) * 100) }}%"></div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-sm font-bold text-slate-600">₺{{ number_format($coupon->min_amount, 2) }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <form action="{{ route('vendor.coupons.toggle-status', $coupon->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="relative inline-flex flex-shrink-0 h-5 w-10 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ $coupon->is_active ? 'bg-emerald-500' : 'bg-slate-200' }}">
                                                <span class="inline-block h-4 w-4 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $coupon->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('vendor.coupons.edit', $coupon->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('vendor.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Bu kuponu silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-slate-100">
                    @foreach($coupons as $coupon)
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <div>
                                        <div class="font-black text-slate-900 tracking-widest text-lg">{{ $coupon->code }}</div>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            @if($coupon->type == 'percentage')
                                                <span class="text-[9px] font-black text-emerald-600 uppercase tracking-tighter">%{{ intval($coupon->value) }} İNDİRİM</span>
                                            @else
                                                <span class="text-[9px] font-black text-blue-600 uppercase tracking-tighter">₺{{ number_format($coupon->value, 2) }} İNDİRİM</span>
                                            @endif
                                            @if($coupon->expires_at && $coupon->expires_at->isPast())
                                                <span class="text-[8px] font-black text-rose-500 uppercase">SÜRESİ DOLDU</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                     <a href="{{ route('vendor.coupons.edit', $coupon->id) }}" class="p-2 text-slate-400 hover:bg-slate-100 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-slate-50 p-3 rounded-2xl">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kullanım</p>
                                    <p class="text-sm font-black text-slate-700">{{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}</p>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-2xl">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Min. Sepet</p>
                                    <p class="text-sm font-black text-slate-700">₺{{ number_format($coupon->min_amount, 2) }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between px-4 py-3 bg-slate-50 rounded-2xl">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">AKTİF DURUM</span>
                                <form action="{{ route('vendor.coupons.toggle-status', $coupon->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="relative inline-flex flex-shrink-0 h-5 w-10 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none {{ $coupon->is_active ? 'bg-emerald-500' : 'bg-slate-200' }}">
                                        <span class="inline-block h-4 w-4 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $coupon->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 border-t border-gray-50">
                    {{ $coupons->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Henüz kupon oluşturmadınız</h3>
                    <p class="text-gray-500 mt-2 max-w-md mx-auto">Müşteri sadakatini artırmak için hemen ilk indirim kuponunuzu oluşturun.</p>
                    <a href="{{ route('vendor.coupons.create') }}" class="mt-6 inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-200 transition-all font-bold text-sm">
                        Kupon Oluştur
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
