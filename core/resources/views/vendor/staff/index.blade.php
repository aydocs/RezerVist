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
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Personel Yönetimi</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Personel Yönetimi</h1>
                <p class="mt-2 text-sm text-gray-600">İşletmenizin personellerini ekleyin, düzenleyin ve rezervasyonlara atayın.</p>
            </div>
            <a href="{{ route('vendor.staff.create') }}" class="inline-flex items-center px-6 py-4 bg-primary text-white rounded-[2rem] font-black text-sm shadow-xl shadow-purple-200 hover:bg-purple-700 transition-all hover:scale-105 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Yeni Personel Ekle
            </a>
        </div>

        <!-- Filters -->
        <div class="mb-6 flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <form action="{{ route('vendor.staff.index') }}" method="GET" class="flex gap-2">
                    <select name="location_id" onchange="this.form.submit()" class="bg-white border-none rounded-2xl px-6 py-3 text-sm font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-primary/20 transition-all appearance-none cursor-pointer">
                        <option value="">Tüm Şubeler</option>
                        <option value="main" {{ request('location_id') == 'main' ? 'selected' : '' }}>Merkez / Ana Şube</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Personel Bilgisi</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Şube</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pozisyon</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">İletişim</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Durum</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($staff as $member)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        @if($member->photo_path)
                                            <img src="{{ asset('storage/' . $member->photo_path) }}" class="w-12 h-12 rounded-2xl object-cover shadow-sm">
                                        @else
                                            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black text-lg">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white {{ $member->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-900 leading-tight">{{ $member->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-primary/5 text-primary rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    {{ $member->location->name ?? 'Merkez' }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    {{ $member->position ?? 'Belirtilmedi' }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-1">
                                    <p class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $member->email ?? '-' }}
                                    </p>
                                    <p class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $member->phone ?? '-' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <form action="{{ route('vendor.staff.toggle-status', $member->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $member->is_active ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-slate-100 text-slate-400 hover:bg-slate-200' }}">
                                        {{ $member->is_active ? 'Aktif' : 'Pasif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('vendor.staff.edit', $member->id) }}" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('vendor.staff.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Bu personeli silmek istediğinize emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-black text-slate-900 mb-1">Henüz Personel Yok</h3>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-6">İşletmenize personel ekleyerek yönetmeye başlayın.</p>
                                    <a href="{{ route('vendor.staff.create') }}" class="inline-flex items-center px-5 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all">
                                        İlk Personelini Ekle
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($staff->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                    {{ $staff->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
