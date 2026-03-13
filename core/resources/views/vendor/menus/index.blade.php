@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Menü & Hizmet Yönetimi</h1>
                <p class="text-sm text-gray-500 mt-1">İşletmenizin ürünlerini ve hizmetlerini yönetin.</p>
            </div>
            <a href="{{ route('vendor.menus.create') }}" class="inline-flex items-center justify-center px-6 py-4 bg-primary text-white rounded-[2rem] font-black text-sm shadow-xl shadow-purple-100 hover:bg-purple-700 transition-all hover:scale-105 active:scale-95 w-full sm:w-auto">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Yeni Ürün Ekle
            </a>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            @if($menus->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">Henüz Menü/Hizmet Eklenmemiş</h3>
                    <p class="text-slate-500 mb-6 max-w-sm mx-auto font-medium">Müşterilerinize sunduğunuz ürünleri veya hizmetleri ekleyerek rezervasyon sırasında seçim yapmalarını sağlayın.</p>
                    <a href="{{ route('vendor.menus.create') }}" class="inline-flex items-center px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-800 transition-all">Hemen Ekle &rarr;</a>
                </div>
            @else
                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Ürün / Hizmet</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Sizin Fiyatınız</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    <span class="flex items-center gap-1">
                                        Müşteri Fiyatı
                                        <div class="group relative inline-block">
                                            <i class="fas fa-info-circle text-slate-300 cursor-help"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-slate-900 text-white text-[9px] p-2 rounded-lg hidden group-hover:block z-10 leading-relaxed font-medium">
                                                Platform komisyonu dahildir.
                                            </div>
                                        </div>
                                    </span>
                                </th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($menus as $menu)
                                <tr class="hover:bg-slate-50/50 transition group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            @if($menu->image)
                                                <img src="{{ Storage::url($menu->image) }}" class="w-12 h-12 rounded-2xl object-cover shadow-sm">
                                            @else
                                                <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 border border-slate-100">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-black text-slate-900">{{ $menu->name }}</div>
                                                <div class="text-xs text-slate-400 font-medium line-clamp-1 truncate max-w-[200px]">{{ $menu->description }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">{{ $menu->category }}</span>
                                    </td>
                                    <td class="px-8 py-6 font-bold text-slate-700">₺{{ number_format($menu->price, 2) }}</td>
                                    <td class="px-8 py-6 font-black text-slate-900">₺{{ number_format($menu->price, 2) }}</td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('vendor.menus.edit', $menu->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('vendor.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card Layout -->
                <div class="md:hidden divide-y divide-slate-100">
                    @foreach($menus as $menu)
                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-4">
                                @if($menu->image)
                                    <img src="{{ Storage::url($menu->image) }}" class="w-16 h-16 rounded-2xl object-cover shadow-sm">
                                @else
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 border border-slate-100">
                                        <i class="fas fa-image text-xl"></i>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                         <div class="font-black text-slate-900 truncate pr-2">{{ $menu->name }}</div>
                                         <div class="flex items-center gap-1">
                                             <a href="{{ route('vendor.menus.edit', $menu->id) }}" class="p-1.5 text-slate-400 hover:bg-slate-100 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                             </a>
                                         </div>
                                    </div>
                                    <span class="bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest inline-block">{{ $menu->category }}</span>
                                </div>
                            </div>
                            
                            <div class="bg-slate-50 p-4 rounded-2xl">
                                <p class="text-[10px] text-slate-400 font-medium italic mb-2 leading-relaxed">"{{ $menu->description ?? 'Açıklama yok.' }}"</p>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-400 font-bold">Liste Fiyatı</span>
                                    <span class="font-black text-slate-900">₺{{ number_format($menu->price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-6 border-t border-gray-100">
                    {{ $menus->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
