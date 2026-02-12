<div class="overflow-x-auto min-h-[400px]">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-white">
                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tarih / Saat</th>
                @if($filterType == 'all')
                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">İşlem Tipi</th>
                @endif
                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kullanıcı</th>
                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Referans</th>
                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tutar</th>
                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Durum</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @php
                $filtered = $transactions->filter(function($tx) use ($filterType) {
                    if($filterType == 'all') return true;
                    return $tx->type == $filterType;
                });
            @endphp
            
            @forelse($filtered as $tx)
            <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                <td class="px-8 py-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs group-hover:bg-white transition-colors">
                            {{ $tx->created_at->format('d.m') }}
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-900">{{ $tx->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                </td>
                @if($filterType == 'all')
                <td class="px-8 py-6">
                    @php
                        $txCfg = match($tx->type) {
                            'topup' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Bakiye Yükleme'],
                            'payment' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'label' => 'Harcama'],
                            'refund' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'label' => 'İade'],
                            default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => $tx->type]
                        };
                    @endphp
                    <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $txCfg['bg'] }} {{ $txCfg['text'] }}">
                        {{ $txCfg['label'] }}
                    </span>
                </td>
                @endif
                <td class="px-8 py-6">
                    <p class="text-sm font-black text-slate-900">{{ $tx->user->name }}</p>
                    <p class="text-[10px] font-bold text-slate-400 lowercase">{{ $tx->user->email }}</p>
                </td>
                <td class="px-8 py-6 text-xs font-bold text-slate-500">
                    #{{ $tx->reference_id ?? '---' }}
                </td>
                <td class="px-8 py-6 text-sm font-black {{ $tx->type == 'topup' ? 'text-emerald-600' : 'text-slate-900' }}">
                    {{ $tx->type == 'topup' ? '+' : '-' }}₺{{ number_format($tx->amount, 2, ',', '.') }}
                </td>
                <td class="px-8 py-6">
                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase bg-slate-50 text-slate-400 border border-slate-100">
                        {{ $tx->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $filterType == 'all' ? '6' : '5' }}" class="px-8 py-24 text-center">
                    <div class="flex flex-col items-center justify-center gap-4">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Veri bulunamadı</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($transactions->hasPages())
    <div class="p-6 bg-slate-50/50 border-t border-slate-100">
        {{ $transactions->links() }}
    </div>
@endif
