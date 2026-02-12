@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Şube Yönetimi</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">İşletmenizin tüm fiziksel şubelerini buradan yönetin.</p>
            </div>
            <a href="{{ route('vendor.locations.create') }}" class="bg-primary text-white px-6 py-3 rounded-2xl font-bold hover:bg-primary/90 transition shadow-lg shadow-primary/30 active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Yeni Şube Ekle
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl font-bold flex items-center gap-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Şube Bilgisi</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">İletişim & Konum</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Durum</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($locations as $location)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black shadow-sm">
                                    {{ substr($location->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 leading-tight group-hover:text-primary transition-colors">{{ $location->name }}</h4>
                                    <p class="text-xs text-slate-400 mt-1 font-medium">{{ $location->address }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="space-y-1">
                                <p class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $location->phone ?: 'Belirtilmedi' }}
                                </p>
                                <p class="text-xs text-slate-400 font-medium flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $location->city }} / {{ $location->district }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @if($location->is_active)
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">Aktif</span>
                            @else
                                <span class="px-3 py-1 bg-slate-50 text-slate-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-slate-100">Pasif</span>
                            @endif
                        </td>
                        <td class="px-6 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('vendor.locations.edit', $location->id) }}" class="p-2 bg-slate-50 text-slate-400 hover:bg-primary-50 hover:text-primary rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('vendor.locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Şubeyi silmek istediğinize emin misiniz?');">
                                    @csrf @method('DELETE')
                                    <button class="p-2 bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-24 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-200 mx-auto mb-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-1">Henüz Şube Eklenmemiş</h3>
                            <p class="text-slate-400 text-sm">İşletmenizin şubelerini ekleyerek yönetmeye başlayın.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($locations->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-50">
                    {{ $locations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
