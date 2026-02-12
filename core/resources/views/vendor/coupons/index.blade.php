@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kupon Yönetimi</h1>
                <p class="text-sm text-gray-500 mt-1">Müşterilerinize özel kampanya ve indirim kuponları oluşturun.</p>
            </div>
            <a href="{{ route('vendor.coupons.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-200 transition-all font-medium text-sm gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Yeni Kupon Oluştur
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-green-700 font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Coupons List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($coupons->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="px-6 py-4">Kupon Kodu</th>
                                <th class="px-6 py-4">İndirim</th>
                                <th class="px-6 py-4">Kullanım / Limit</th>
                                <th class="px-6 py-4">Min. Sepet</th>
                                <th class="px-6 py-4">Son Kullanma</th>
                                <th class="px-6 py-4">Durum</th>
                                <th class="px-6 py-4 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($coupons as $coupon)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                            </div>
                                            <div>
                                                <span class="font-bold text-gray-900 font-mono tracking-wide">{{ $coupon->code }}</span>
                                                @if($coupon->expires_at && $coupon->expires_at->isPast())
                                                    <span class="ml-2 px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600">SÜRESİ DOLDU</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($coupon->type == 'percentage')
                                            <span class="text-sm font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">%{{ intval($coupon->value) }} İndirim</span>
                                        @else
                                            <span class="text-sm font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">₺{{ number_format($coupon->value, 2) }} İndirim</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-700">{{ $coupon->used_count }} <span class="text-gray-400 font-normal">kez kullanıldı</span></span>
                                            @if($coupon->max_uses)
                                                <div class="w-24 h-1.5 bg-gray-100 rounded-full mt-1.5 overflow-hidden">
                                                    <div class="h-full bg-indigo-500 rounded-full" style="width: {{ min(100, ($coupon->used_count / $coupon->max_uses) * 100) }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-400 mt-1">Limit: {{ $coupon->max_uses }}</span>
                                            @else
                                                <span class="text-xs text-gray-400 mt-1">Limitsiz</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600 font-medium">₺{{ number_format($coupon->min_amount, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($coupon->expires_at)
                                            <span class="text-sm text-gray-600">{{ $coupon->expires_at->format('d.m.Y') }}</span>
                                            <span class="text-xs text-gray-400 block">{{ $coupon->expires_at->diffForHumans() }}</span>
                                        @else
                                            <span class="text-sm text-gray-400">Süresiz</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('vendor.coupons.toggle-status', $coupon->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 {{ $coupon->is_active ? 'bg-green-500' : 'bg-gray-200' }}">
                                                <span class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $coupon->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('vendor.coupons.edit', $coupon->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-indigo-600 hover:border-indigo-200 transition-colors shadow-sm" title="Düzenle">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('vendor.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Bu kuponu silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-red-600 hover:border-red-200 transition-colors shadow-sm" title="Sil">
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
