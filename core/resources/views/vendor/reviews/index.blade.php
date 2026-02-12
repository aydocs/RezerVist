@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pb-20 font-inter pt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Değerlendirmeler</h1>
                <p class="text-slate-500 font-medium mt-1">Müşterilerinizin işletmeniz hakkındaki görüşleri.</p>
            </div>
            <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                <span class="text-sm font-bold text-slate-500">Ortalama Puan:</span>
                <span class="text-lg font-black text-yellow-500 flex items-center gap-1">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    {{ number_format(Auth::user()->ownedBusiness->reviews()->avg('rating'), 1) }}
                </span>
            </div>
        </div>

        <!-- Reviews Grid -->
        <div class="space-y-6">
            @forelse($reviews as $review)
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row gap-6 hover:shadow-lg hover:border-indigo-100 transition-all duration-300">
                <!-- User Info -->
                <div class="flex items-center gap-4 min-w-[200px]">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-indigo-50 to-violet-50 flex items-center justify-center text-indigo-600 font-black text-xl border border-indigo-100">
                        {{ strtoupper(substr($review->user->name ?? 'M', 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900">{{ $review->user->name ?? 'Misafir' }}</h4>
                        <p class="text-xs text-slate-400 font-medium">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Review Content -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex text-yellow-400">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                        </div>
                        
                        @if($review->is_reported)
                            <span class="text-[10px] font-black text-amber-500 bg-amber-50 px-2 py-1 rounded-lg border border-amber-100 uppercase tracking-wider">İnceleme Altında</span>
                        @else
                            <form action="{{ route('reviews.report', $review->id) }}" method="POST" onsubmit="return confirm('Bu yorumu bildirmek istediğinize emin misiniz? Admin ekibi tarafından incelenecektir.')">
                                @csrf
                                <button class="text-[10px] font-black text-rose-400 hover:text-rose-600 transition-colors uppercase tracking-widest border border-rose-100 px-2 py-1 rounded-lg hover:bg-rose-50 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    BİLDİR
                                </button>
                            </form>
                        @endif
                    </div>
                    @if($review->comment)
                    <p class="text-slate-600 leading-relaxed italic">"{{ $review->comment }}"</p>
                    @else
                    <p class="text-slate-400 italic">Yorum yapılmamış.</p>
                    @endif
                </div>

                <!-- Context (Reservation Date or Menu Items - Optional expansion) -->
                <!-- Ideally link to reservation if relationship exists -->
            </div>
            @empty
            <div class="bg-white rounded-[24px] p-12 text-center border border-gray-100">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Henüz Değerlendirme Yok</h3>
                <p class="text-slate-500 mt-2">İşletmeniz için henüz hiç yorum yapılmamış.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
