@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Yönetim Paneli
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Kuponlar</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kupon Yönetimi</h1>
                <p class="mt-2 text-sm text-gray-600">Platform genelindeki kampanya kuponlarını yönetin ve oluşturun.</p>
            </div>
            <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-bold rounded-2xl shadow-lg shadow-primary/20 text-white bg-gradient-to-r from-primary to-purple-700 hover:from-primary/90 hover:to-purple-800 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Yeni Kupon Oluştur
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Kupon Kodu</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Tip / Değer</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Min. Tutar</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Kullanım</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Geçerlilik</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Durum</th>
                            <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($coupons as $coupon)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary font-bold text-xs ring-4 ring-primary/5 mr-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-gray-900 font-mono">{{ $coupon->code }}</div>
                                            <div class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">Eklenme: {{ $coupon->created_at->format('d.m.Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($coupon->type === 'percentage')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                            %{{ number_format($coupon->value, 0) }} İndirim
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                            ₺{{ number_format($coupon->value, 2) }} İndirim
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-700">₺{{ number_format($coupon->min_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-1 h-2 w-16 bg-gray-100 rounded-full mr-2 overflow-hidden">
                                            @php
                                                $percentage = $coupon->max_uses ? ($coupon->used_count / $coupon->max_uses) * 100 : 0;
                                            @endphp
                                            <div class="h-full bg-primary rounded-full" style="width: {{ min(100, $percentage) }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-gray-600">
                                            {{ $coupon->used_count }}{{ $coupon->max_uses ? '/' . $coupon->max_uses : ' (Sınırsız)' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($coupon->expires_at)
                                        <div class="text-sm font-medium {{ $coupon->expires_at->isPast() ? 'text-red-500' : 'text-gray-900' }}">
                                            {{ $coupon->expires_at->format('d.m.Y H:i') }}
                                        </div>
                                        <div class="text-[10px] text-gray-400">
                                            {{ $coupon->expires_at->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="text-xs font-bold text-gray-400 italic">Süre Sınırı Yok</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none ring-2 ring-primary/20 {{ $coupon->is_active ? 'bg-primary' : 'bg-gray-200' }}">
                                            <span class="translate-x-0 pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 {{ $coupon->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="inline-flex items-center p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu kuponu silmek istediğinize emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-inner">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    </div>
                                    <p class="text-base font-bold text-gray-400">Henüz bir kupon oluşturulmamış.</p>
                                    <a href="{{ route('admin.coupons.create') }}" class="mt-4 inline-flex items-center text-primary font-bold hover:underline">İlk kuponu şuradan ekleyebilirsin</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($coupons->hasPages())
                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                    {{ $coupons->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
