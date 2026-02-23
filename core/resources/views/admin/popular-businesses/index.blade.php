@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1700px] mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b pb-8 border-slate-200">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Yönetim</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Performans</span>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Popüler İşletmeler</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">İşletme <span class="text-purple-600">Analitiği</span></h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-3">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Canlı Veri</span>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm flex items-center gap-2 group">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Panele Dön
                </a>
            </div>
        </div>

        <!-- Businesses Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Sıralama</th>
                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">İşletme Kimliği</th>
                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Sektör</th>
                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">İşlem Hacmi</th>
                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Kullanıcı Deneyimi</th>
                            <th class="px-8 py-5 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Raporlama</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($businesses as $index => $business)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white text-xs font-black shadow-lg shadow-slate-900/10 group-hover:bg-purple-600 transition-colors">
                                        #{{ $businesses->firstItem() + $index }}
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        @if($business->image)
                                            <div class="w-10 h-10 rounded-xl overflow-hidden border border-slate-200 shadow-sm flex-shrink-0">
                                                <img class="w-full h-full object-cover" src="{{ asset('storage/' . $business->image) }}" alt="">
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-black text-sm uppercase flex-shrink-0">
                                                {{ substr($business->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-slate-900 uppercase tracking-tight group-hover:text-purple-600 transition-colors">{{ $business->name }}</span>
                                            <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                                <i class="fa-solid fa-location-dot text-[8px]"></i>
                                                {{ $business->location ?? 'Konum Tanımsız' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 bg-white border border-slate-200 text-slate-600 rounded text-[9px] font-black uppercase tracking-widest">
                                        {{ $business->category->name ?? 'Genel' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-xs font-black text-slate-900">{{ number_format($business->reservations_count) }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Rezervasyon</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="flex items-center gap-0.5">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="fa-solid fa-star text-[9px] {{ $i < floor($business->rating) ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="text-[10px] font-black text-slate-700 bg-slate-100 px-2 py-0.5 rounded">{{ number_format($business->rating, 1) }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-600 hover:bg-purple-50 hover:text-purple-600 border border-slate-200 hover:border-purple-200 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">
                                        <i class="fa-solid fa-chart-line"></i>
                                        Detaylar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fa-solid fa-building-circle-exclamation text-4xl mb-4"></i>
                                        <p class="text-[10px] font-black uppercase tracking-widest">Henüz veri toplanmadı.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $businesses->links('vendor.pagination.console') }}
        </div>

    </div>
</div>

<style>
    /* Pagination Styling */
    .pagination { @apply flex items-center justify-center gap-2; }
    .page-item { @apply inline-block; }
    .page-item .page-link { 
        @apply w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-400 text-xs font-black transition-all hover:bg-slate-50 hover:text-slate-900;
    }
    .page-item.active .page-link {
        @apply bg-purple-600 border-purple-600 text-white shadow-lg shadow-purple-600/20;
    }
    .page-item.disabled .page-link { @apply opacity-50 cursor-not-allowed bg-slate-50; }
</style>
@endsection
