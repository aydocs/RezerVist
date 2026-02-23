@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1700px] mx-auto space-y-8">
        
        <!-- Header & Top Nav -->
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b pb-8 border-slate-200">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Yönetim</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Finansal Operasyonlar</span>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Para Çekme Talepleri</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Ödeme <span class="text-purple-600">Yönetimi</span></h1>
            </div>

            <div class="flex items-center gap-3 mt-4 md:mt-0">
                <div class="px-6 py-3 bg-white border border-slate-200 rounded-2xl shadow-sm relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-1 h-full bg-amber-500 opacity-20"></div>
                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Bekleyen İşlem Hacmi</div>
                    <div class="text-xl font-black text-slate-900 tracking-tighter">₺{{ number_format($withdrawals->where('status', 'pending')->sum('amount'), 2, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Density Optimized Financial Table -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Alacaklı Verisi</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Hakediş Tutarı</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Ödeme Kanalları (IBAN)</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Mutabakat</th>
                        <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Yönetim</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($withdrawals as $w)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-4">
                                <div class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 flex items-center justify-center font-black text-[10px] group-hover:bg-slate-900 group-hover:text-white group-hover:border-slate-900 transition-all duration-300">
                                    {{ substr($w->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-[10px] font-black text-slate-900 tracking-tight">{{ $w->user->name }}</div>
                                    <div class="text-[8px] text-slate-400 font-bold uppercase tracking-tight truncate max-w-[150px]">{{ $w->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-4">
                            <div class="text-[11px] font-black text-slate-900 tracking-tighter">₺{{ number_format($w->amount, 2, ',', '.') }}</div>
                            <div class="text-[8px] font-bold text-purple-600/60 uppercase tracking-widest">{{ $w->created_at->format('d.m.Y H:i') }}</div>
                        </td>
                        <td class="px-8 py-4">
                            <div class="flex flex-col gap-0.5 max-w-[280px]">
                                <div class="text-[10px] font-black text-slate-600 font-mono tracking-tighter flex items-center gap-2 cursor-pointer hover:text-purple-600 transition-colors group/iban" onclick="navigator.clipboard.writeText('{{ $w->iban }}')">
                                    <span class="truncate">{{ $w->iban }}</span>
                                    <i class="fa-regular fa-copy text-[8px] opacity-0 group-hover/iban:opacity-100 transition-opacity"></i>
                                </div>
                                <div class="text-[8px] text-slate-400 font-black uppercase tracking-tighter whitespace-nowrap overflow-hidden text-ellipsis">
                                    {{ $w->account_holder }} <span class="mx-1 text-slate-300">•</span> {{ $w->bank_name ?? 'Genel Banka' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-4 text-center">
                            @php
                                $statusBadge = match($w->status) {
                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    default => 'bg-slate-50 text-slate-400 border-slate-100'
                                };
                                $statusLabel = match($w->status) {
                                    'pending' => 'Onay Bekliyor',
                                    'completed' => 'Ödeme Yapıldı',
                                    'rejected' => 'Talep Reddedildi',
                                    default => $w->status
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-[8px] font-black uppercase tracking-[0.1em] border {{ $statusBadge }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-8 py-4 whitespace-nowrap">
                            @if($w->status === 'pending')
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.withdrawals.update', $w->id) }}" method="POST" onsubmit="return confirm('İşlemi onaylayıp ödeme yapıldı olarak işaretlemek üzeresiniz. Devam edilsin mi?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button class="px-4 py-2 bg-slate-900 text-white text-[8px] font-black rounded-lg hover:bg-purple-600 transition-all uppercase tracking-widest shadow-lg shadow-slate-900/10">
                                        ONAYLA & ÖDE
                                    </button>
                                </form>
                                <form action="{{ route('admin.withdrawals.update', $w->id) }}" method="POST" onsubmit="return confirm('Bu ödeme talebi reddedilecektir. Onaylıyor musunuz?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="px-4 py-2 bg-white text-slate-400 border border-slate-200 text-[8px] font-black rounded-lg hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all uppercase tracking-widest">
                                        REDDET
                                    </button>
                                </form>
                            </div>
                            @else
                                <div class="flex justify-end items-center gap-2 opacity-30 select-none">
                                    <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                                    <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Arşivlendi</div>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center opacity-20">
                            <i class="fa-solid fa-money-check-dollar text-5xl mb-4"></i>
                            <p class="text-[9px] font-black uppercase tracking-[0.2em]">Aktif ödeme talebi bulunamadı</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
        <div class="flex justify-center mt-6">
            {{ $withdrawals->links() }}
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
