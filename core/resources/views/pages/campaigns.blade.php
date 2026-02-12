@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900">Kampanyalar</h1>
            <p class="text-gray-500 mt-2">En özel fırsatları ve indirimleri kaçırmayın.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($campaigns as $campaign)
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group border border-gray-100 h-full flex flex-col">
                <div class="relative h-56 overflow-hidden bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                    <div class="text-center text-white p-8">
                        <div class="text-6xl font-black mb-4">
                            @if($campaign->type === 'percentage')
                                %{{ number_format($campaign->value, 0) }}
                            @else
                                ₺{{ number_format($campaign->value, 0) }}
                            @endif
                        </div>
                        <div class="text-2xl font-bold uppercase tracking-widest">İNDİRİM</div>
                    </div>
                    
                    <div class="absolute top-4 left-4 bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg uppercase tracking-wider">
                        {{ $campaign->type === 'percentage' ? 'Yüzde İndirim' : 'Sabit İndirim' }}
                    </div>
                    
                    @if($campaign->expires_at)
                        <div class="absolute bottom-4 left-4 bg-black/60 backdrop-blur-md text-white text-[10px] px-2 py-1 rounded">
                            Son Kullanma: {{ $campaign->expires_at->translatedFormat('d F Y') }}
                        </div>
                    @else
                        <div class="absolute bottom-4 left-4 bg-emerald-500/80 backdrop-blur-md text-white text-[10px] px-2 py-1 rounded font-bold">
                            Süresiz Geçerli
                        </div>
                    @endif
                </div>
                <div class="p-8 flex flex-col flex-grow">
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition">
                        {{ $campaign->type === 'percentage' ? $campaign->value . '% İndirim Kuponu' : $campaign->value . '₺ İndirim Kuponu' }}
                    </h3>
                    <p class="text-gray-500 text-sm mb-6 leading-relaxed flex-grow">
                        @if($campaign->min_amount)
                            {{ $campaign->min_amount }}₺ ve üzeri alışverişlerinizde geçerlidir.
                        @else
                            Tüm alışverişlerinizde geçerlidir.
                        @endif
                        
                        @if($campaign->max_uses)
                            <span class="block mt-2 text-xs text-gray-400">
                                Kalan Kullanım: {{ max(0, $campaign->max_uses - $campaign->used_count) }} / {{ $campaign->max_uses }}
                            </span>
                        @endif
                    </p>
                    
                    <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200 flex items-center justify-between group/code">
                        <code class="text-primary font-black tracking-widest text-lg">{{ $campaign->code }}</code>
                        <button onclick="copyToClipboard('{{ $campaign->code }}')" class="p-2 text-gray-400 hover:text-primary transition bg-white rounded-lg shadow-sm border border-gray-100 flex items-center gap-2 text-xs font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            <span>Kopyala</span>
                        </button>
                    </div>

                    <a href="/search" class="text-primary font-bold hover:text-primary-dark transition text-sm flex items-center group/btn">
                        Hemen Kullan <svg class="w-4 h-4 ml-1 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
            @empty
             <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-gray-100">
                <p class="text-gray-500 text-lg">Şu anda aktif kampanya bulunmamaktadır.</p>
                <p class="text-gray-400 mt-2">Daha sonra tekrar kontrol ediniz.</p>
             </div>
            @endforelse
        </div>

        <div class="mt-16 text-center">
            <p class="text-gray-500 mb-6">Daha fazla mekan ve fırsat mı arıyorsunuz?</p>
            <a href="/search" class="inline-flex items-center px-8 py-4 bg-white border border-gray-200 text-gray-900 font-bold rounded-2xl hover:bg-gray-50 hover:border-primary transition-all shadow-sm hover:shadow-md group">
                Keşfetmeye Başla
                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>
</div>
@section('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Kampanya kodu kopyalandı!', 'success');
        });
    }
</script>
@endsection
@endsection
