<div class="overflow-x-auto">
    <table class="w-full text-left border-separate border-spacing-0">
        <thead>
            <tr class="bg-slate-50/50">
                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">Zaman Damgası</th>
                @if($filterType == 'all')
                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">İşlem Modeli</th>
                @endif
                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">Yetkili / Kullanıcı</th>
                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">Referans</th>
                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">Net Tutar</th>
                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-200">Durum</th>
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
            <tr class="group hover:bg-slate-50/30 transition-colors">
                <td class="px-8 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-500 group-hover:bg-purple-50 group-hover:text-purple-600 transition-colors border border-slate-200">
                            {{ $tx->created_at->format('d/m') }}
                        </div>
                        <div class="text-[10px] font-black text-slate-900">{{ $tx->created_at->format('H:i') }}</div>
                    </div>
                </td>
                @if($filterType == 'all')
                <td class="px-8 py-4">
                    @php
                        $txCfg = match($tx->type) {
                            'topup' => ['bg' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'label' => 'Bakiye Yükleme'],
                            'payment' => ['bg' => 'bg-purple-50 text-purple-600 border-purple-100', 'label' => 'Cüzdan Ödeme'],
                            'refund' => ['bg' => 'bg-rose-50 text-rose-600 border-rose-100', 'label' => 'İade İşlemi'],
                            default => ['bg' => 'bg-slate-50 text-slate-600 border-slate-100', 'label' => $tx->type]
                        };
                    @endphp
                    <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest border {{ $txCfg['bg'] }}">
                        {{ $txCfg['label'] }}
                    </span>
                </td>
                @endif
                <td class="px-8 py-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-900">{{ $tx->user->name }}</span>
                        <span class="text-[8px] font-bold text-slate-400 lowercase tracking-tight">{{ $tx->user->email }}</span>
                    </div>
                </td>
                <td class="px-8 py-4 text-[9px] font-mono font-black text-slate-300">
                    #{{ $tx->reference_id ?? '---' }}
                </td>
                <td class="px-8 py-4">
                    <span class="text-[10px] font-black tracking-tighter {{ $tx->type == 'topup' ? 'text-emerald-600' : 'text-slate-900' }}">
                        {{ $tx->type == 'topup' ? '+' : '-' }}₺{{ number_format($tx->amount, 2, ',', '.') }}
                    </span>
                </td>
                <td class="px-8 py-4">
                    <div class="flex items-center gap-1.5 opacity-60">
                        <div class="w-1 h-1 rounded-full bg-slate-400"></div>
                        <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest">{{ strtoupper($tx->status) }}</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $filterType == 'all' ? '6' : '5' }}" class="px-8 py-20 text-center opacity-20">
                    <i class="fa-solid fa-file-invoice-dollar text-4xl mb-3"></i>
                    <p class="text-[9px] font-black uppercase tracking-widest">Analiz edilecek veri bulunamadı</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($transactions->hasPages())
    <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-100">
        {{ $transactions->links() }}
    </div>
@endif
