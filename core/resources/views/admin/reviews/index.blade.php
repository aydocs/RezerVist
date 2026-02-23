@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1700px] mx-auto space-y-8">
        
        <!-- Professional Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b pb-8 border-slate-200">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Yönetim</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>İçerik Denetimi</span>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Kullanıcı Değerlendirmeleri</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">İtibar <span class="text-purple-600">Moderasyonu</span></h1>
            </div>

            <div class="flex items-center gap-1.5 bg-white p-1.5 rounded-2xl border border-slate-200 shadow-sm mt-4 md:mt-0">
                <a href="{{ route('admin.reviews.index', ['status' => 'approved', 'reported' => 0]) }}" 
                   class="px-5 py-2.5 rounded-xl text-[9px] font-black tracking-widest uppercase transition-all {{ ($status === 'approved' && !$showReported) ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/10' : 'text-slate-400 hover:text-slate-900 hover:bg-slate-50' }}">
                    YAYINLANAN
                </a>
                <a href="{{ route('admin.reviews.index', ['reported' => 1]) }}" 
                   class="px-5 py-2.5 rounded-xl text-[9px] font-black tracking-widest uppercase transition-all relative {{ $showReported ? 'bg-amber-100 text-amber-700 border border-amber-200/50' : 'text-slate-400 hover:text-slate-900 hover:bg-slate-50' }}">
                    BİLDİRİLENLER
                    @if($showReported)
                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.reviews.index', ['status' => 'pending', 'reported' => 0]) }}" 
                   class="px-5 py-2.5 rounded-xl text-[9px] font-black tracking-widest uppercase transition-all {{ ($status === 'pending' && !$showReported) ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/20' : 'text-slate-400 hover:text-slate-900 hover:bg-slate-50' }}">
                    BEKLEYENLER
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-3 px-6 py-3 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-2xl border border-emerald-100 w-fit animate-in fade-in slide-in-from-top-4">
                <i class="fa-solid fa-circle-check text-xs"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Analiz Edilen Kullanıcı</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Hedef İşletme</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Değerlendirme Verisi</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right whitespace-nowrap">Kontrol Paneli</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-slate-50/30 transition-colors {{ $review->is_reported ? 'bg-amber-50/20' : '' }} group">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 flex items-center justify-center font-black text-[11px] relative overflow-hidden transition-all duration-300 group-hover:bg-slate-900 group-hover:text-white group-hover:border-slate-900">
                                    {{ substr($review->user->name, 0, 1) }}
                                    @if($review->user->is_review_blocked)
                                        <div class="absolute inset-0 bg-rose-500/10 flex items-center justify-center backdrop-blur-[1px]">
                                            <i class="fa-solid fa-ban text-[8px] text-rose-500"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[11px] font-black text-slate-900 tracking-tight">{{ $review->user->name }}</p>
                                    <div class="flex items-center gap-2 mt-1 text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                                        <span>{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                        <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                                        <span class="{{ $review->user->review_stats['deleted'] > 3 ? 'text-rose-500 animate-pulse' : '' }}">{{ $review->user->review_stats['deleted'] }} OTOSİLİNMİŞ</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-building-circle-check text-[10px] text-purple-600/40"></i>
                                <p class="text-[10px] font-black text-slate-700 uppercase tracking-tighter">{{ $review->business->name }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-5 max-w-lg">
                            <div class="flex items-center gap-3 mb-2.5">
                                <div class="flex items-center gap-0.5">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="fa-solid fa-star text-[8px] {{ $i <= $review->rating ? 'text-amber-400 shadow-sm shadow-amber-400/20' : 'text-slate-200' }}"></i>
                                    @endfor
                                </div>
                                @if($review->is_reported)
                                    <span class="bg-amber-100 text-amber-700 text-[7px] font-black px-1.5 py-0.5 rounded border border-amber-200 uppercase tracking-[0.2em] animate-pulse">KRİTİK BİLDİRİM</span>
                                @endif
                                @if($review->status === 'pending')
                                    <span class="bg-purple-100 text-purple-600 text-[7px] font-black px-1.5 py-0.5 rounded border border-purple-200 uppercase tracking-[0.2em]">İNCELEME ALTINDA</span>
                                @endif
                            </div>
                            <div class="relative pl-4 border-l-2 border-slate-100 group-hover:border-purple-200 transition-colors">
                                <p class="text-[11px] text-slate-600 font-medium leading-relaxed italic">"{{ $review->comment }}"</p>
                            </div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-right">
                            <div class="flex justify-end items-center gap-2">
                                @if($review->is_reported)
                                <form action="{{ route('admin.reviews.keep', $review->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-emerald-500 text-white px-4 py-2 rounded-xl text-[8px] font-black hover:bg-emerald-600 transition-all uppercase tracking-widest shadow-lg shadow-emerald-500/10 active:scale-95">GÜVENLİ İŞARETLE</button>
                                </form>
                                @endif

                                @if($review->status === 'pending')
                                <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[8px] font-black hover:bg-purple-600 transition-all uppercase tracking-widest shadow-lg shadow-slate-900/10 active:scale-95">YAYINA AL</button>
                                </form>
                                @endif

                                <div class="flex items-center gap-1.5 ml-2">
                                    <form action="{{ route('admin.users.toggle-review-block', $review->user_id) }}" method="POST">
                                        @csrf
                                        <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all group/ban" title="Yorum Yapmayı Engelle">
                                            <i class="fa-solid fa-user-lock text-[10px]"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('İlgili yorum kalıcı olarak kaldırılacaktır. Onaylıyor musunuz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all group/trash" title="Kalıcı Olarak Sil">
                                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-32 text-center opacity-20">
                            <i class="fa-solid fa-comments-dollar text-5xl mb-4"></i>
                            <p class="text-[9px] font-black uppercase tracking-[0.2em]">Modere edilecek yorum bulunmuyor</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($reviews->hasPages())
        <div class="flex justify-center mt-6">
            {{ $reviews->links() }}
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
