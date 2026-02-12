@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-28 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Değerlendirme Moderasyonu</h1>
                <p class="text-sm text-slate-500 font-medium">İşletmelere yapılan yorumları yönetin.</p>
            </div>
            
            <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 overflow-x-auto max-w-full">
                <a href="{{ route('admin.reviews.index', ['status' => 'approved', 'reported' => 0]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ ($status === 'approved' && !$showReported) ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-slate-500 hover:bg-slate-50' }}">
                    YAYINDA
                </a>
                <a href="{{ route('admin.reviews.index', ['reported' => 1]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $showReported ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50' }}">
                    BİLDİRİLENLER
                </a>
                <a href="{{ route('admin.reviews.index', ['status' => 'pending', 'reported' => 0]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ ($status === 'pending' && !$showReported) ? 'bg-amber-500 text-white shadow-lg shadow-amber-200' : 'text-slate-500 hover:bg-slate-50' }}">
                    BEKLEYEN
                </a>
                <a href="{{ route('admin.reviews.index', ['status' => 'rejected', 'reported' => 0]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ ($status === 'rejected' && !$showReported) ? 'bg-rose-600 text-white shadow-lg shadow-rose-200' : 'text-slate-500 hover:bg-slate-50' }}">
                    REDDEDİLEN
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kullanıcı</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">İşletme</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Puan & Yorum</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tarih</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ $review->is_reported ? 'bg-amber-50/30' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-black text-sm relative">
                                    {{ substr($review->user->name, 0, 1) }}
                                    @if($review->user->is_review_blocked)
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 rounded-full border-2 border-white flex items-center justify-center">
                                            <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-[120px]">
                                    <p class="text-sm font-black text-slate-900 leading-tight">{{ $review->user->name }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">İSTATİSTİK:</span>
                                        <span class="text-[9px] font-black text-slate-600">{{ $review->user->review_stats['total'] }} Toplam</span>
                                        <span class="text-[9px] font-black {{ $review->user->review_stats['deleted'] > 3 ? 'text-rose-600' : 'text-slate-400' }}">{{ $review->user->review_stats['deleted'] }} Silinen</span>
                                    </div>
                                    <form action="{{ route('admin.users.toggle-review-block', $review->user_id) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button class="text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded {{ $review->user->is_review_blocked ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} hover:opacity-80 transition-opacity">
                                            {{ $review->user->is_review_blocked ? 'ENGELİ KALDIR' : 'YORUM ENGELLE' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-700 leading-tight">{{ $review->business->name }}</p>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <div class="flex items-center gap-3 mb-1">
                                <div class="flex items-center gap-0.5">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                @if($review->is_reported)
                                    <span class="bg-amber-100 text-amber-700 text-[9px] font-black px-1.5 py-0.5 rounded animate-pulse">İŞLETME BİLDİRDİ</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 italic">"{{ $review->comment }}"</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[10px] font-bold text-slate-500">{{ $review->created_at->format('d.m.Y') }}</p>
                            <p class="text-[9px] font-medium text-slate-400">{{ $review->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                @if($review->is_reported)
                                <form action="{{ route('admin.reviews.keep', $review->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-emerald-500 text-white px-4 py-2 rounded-xl text-[10px] font-black hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-200">GÜVENLİ</button>
                                </form>
                                @endif

                                @if($review->status === 'pending')
                                <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-emerald-500 text-white px-4 py-2 rounded-xl text-[10px] font-black hover:bg-emerald-600 transition-colors">ONAYLA</button>
                                </form>
                                @endif

                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Yorum kalıcı olarak silinecek. Emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-rose-50 text-rose-500 p-2.5 rounded-xl hover:bg-rose-100 transition-colors group" title="SİL">
                                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-400 font-medium">Bu durumda henüz yorum bulunmuyor.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
