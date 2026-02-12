@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('vendor.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary transition-colors">
                        Panel
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Müşteri Veritabanı (CRM)</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Müşteri Veritabanı</h1>
                <p class="mt-2 text-sm text-gray-600 font-medium">İşletmenizi tercih eden müşterilerin analizi ve geçmişi.</p>
            </div>
            <div class="flex gap-4">
                 <div class="bg-indigo-50 px-6 py-4 rounded-[2rem] border border-indigo-100">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest leading-none mb-1">Toplam Müşteri</p>
                    <p class="text-xl font-black text-indigo-700">{{ $customers->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Müşteri</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Rezervasyon</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Toplam Harcama</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Son Ziyaret</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($customers as $customer)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black text-lg">
                                        {{ substr($customer->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-900 leading-tight">{{ $customer->user->name }}</p>
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $customer->user->phone ?? $customer->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    {{ $customer->total_reservations }} Rezervasyon
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-emerald-600">₺{{ number_format($customer->total_spent, 2, ',', '.') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-bold text-slate-600">{{ \Carbon\Carbon::parse($customer->last_reservation)->translatedFormat('d F Y') }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ \Carbon\Carbon::parse($customer->last_reservation)->diffForHumans() }}</p>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <button class="px-4 py-2 bg-slate-50 text-slate-400 hover:bg-primary/10 hover:text-primary rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Detaylar</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mx-auto mb-6">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 mb-2">Henüz Müşteri Kaydı Yok</h3>
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Rezervasyon tamamlayan müşterileriniz burada listelenecektir.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($customers->hasPages())
                <div class="px-8 py-6 border-t border-slate-50">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
