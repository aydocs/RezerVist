@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pt-28 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Para Çekme Talepleri</h1>
            <p class="text-sm text-slate-500 font-medium">İşletme sahiplerinin bekleyen ödeme talepleri.</p>
        </div>

        <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">İşletme Sahibi</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tutar</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banka Bilgileri</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Durum</th>
                        <th class="px-6 py-4 text-[10px] font-black text task-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($withdrawals as $w)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-sm">
                                    {{ substr($w->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $w->user->name }}</p>
                                    <p class="text-xs text-slate-400 font-medium">{{ $w->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-black text-indigo-600">₺{{ number_format($w->amount, 2, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-slate-700 font-mono">{{ $w->iban }}</p>
                            <p class="text-[10px] text-slate-400 font-medium">{{ $w->account_holder }} / {{ $w->bank_name ?? 'Banka Belirtilmedi' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase
                                @if($w->status === 'pending') bg-amber-50 text-amber-600
                                @elseif($w->status === 'completed') bg-emerald-50 text-emerald-600
                                @elseif($w->status === 'rejected') bg-rose-50 text-rose-600
                                @else bg-slate-50 text-slate-600 @endif">
                                {{ $w->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($w->status === 'pending')
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.withdrawals.update', $w->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button class="bg-emerald-500 text-white px-4 py-2 rounded-xl text-[10px] font-black hover:bg-emerald-600 transition-colors">ÖDENDİ</button>
                                </form>
                                <form action="{{ route('admin.withdrawals.update', $w->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="bg-rose-100 text-rose-600 px-4 py-2 rounded-xl text-[10px] font-black hover:bg-rose-200 transition-colors">REDDET</button>
                                </form>
                            </div>
                            @else
                            <span class="text-[10px] font-black text-slate-300 uppercase">İşlem Tamamlandı</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <p class="text-sm text-slate-400 font-medium">Bekleyen talep bulunmuyor.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-8">
            {{ $withdrawals->links() }}
        </div>
    </div>
</div>
@endsection
