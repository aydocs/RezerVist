@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Menü & Hizmet Yönetimi</h1>
                <p class="text-gray-500 mt-1">İşletmenizin ürünlerini ve hizmetlerini yönetin.</p>
            </div>
            <a href="{{ route('vendor.menus.create') }}" class="px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-purple-700 transition flex items-center gap-2 shadow-lg shadow-primary/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Yeni Ekle
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($menus->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Henüz Menü/Hizmet Eklenmemiş</h3>
                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">Müşterilerinize sunduğunuz ürünleri veya hizmetleri ekleyerek rezervasyon sırasında seçim yapmalarını sağlayın.</p>
                    <a href="{{ route('vendor.menus.create') }}" class="text-primary font-bold hover:underline">Hemen Ekle &rarr;</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-600">Ürün / Hizmet</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-600">Kategori</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-600">Sizin Fiyatınız</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-600">
                                    <span class="flex items-center gap-1">
                                        Müşteri Fiyatı
                                        <div class="group relative">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-gray-900 text-white text-xs p-2 rounded hidden group-hover:block z-10">
                                                Platform komisyonu dahildir.
                                            </div>
                                        </div>
                                    </span>
                                </th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-600">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($menus as $menu)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            @if($menu->image)
                                                <img src="{{ Storage::url($menu->image) }}" class="w-12 h-12 rounded-lg object-cover">
                                            @else
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-gray-900">{{ $menu->name }}</div>
                                                <div class="text-xs text-gray-500 line-clamp-1">{{ $menu->description }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">{{ $menu->category }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ number_format($menu->price, 2) }} ₺</td>
                                    <td class="px-6 py-4 font-bold text-primary">{{ number_format($menu->price, 2) }} ₺</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('vendor.menus.edit', $menu->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('vendor.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
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
                <div class="p-6 border-t border-gray-100">
                    {{ $menus->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
