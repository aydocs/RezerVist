@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 pt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.blog.index') }}" class="p-3 bg-white rounded-2xl border border-slate-200 text-slate-600 hover:text-primary transition-all shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-1">Kategori Yönetimi</h1>
                <p class="text-slate-500 font-medium">Blog yazılarınızın kategorilerini buradan düzenleyin</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Add Category Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100 sticky top-32">
                    <h3 class="text-xl font-black text-slate-900 mb-6">Yeni Kategori</h3>
                    <form action="{{ route('admin.blog.categories.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-black text-slate-400 uppercase tracking-widest mb-2">Kategori Adı</label>
                            <input type="text" name="name" required class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-slate-900 placeholder:text-slate-300" placeholder="Örn: Sektör Haberleri">
                        </div>
                        <div>
                            <label class="block text-sm font-black text-slate-400 uppercase tracking-widest mb-2">Açıklama (Opsiyonel)</label>
                            <textarea name="description" rows="4" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-slate-900 placeholder:text-slate-300" placeholder="Kategori hakkında kısa bilgi..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-4 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all uppercase tracking-widest text-sm">
                            Kategoriyi Kaydet
                        </button>
                    </form>
                </div>
            </div>

            <!-- Categories List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                                <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Yazı Sayısı</th>
                                <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="font-black text-slate-900 text-lg">{{ $category->name }}</div>
                                    <div class="text-sm font-medium text-slate-400 truncate max-w-sm">{{ $category->description ?: 'Açıklama belirtilmemiş' }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="inline-flex items-center px-4 py-2 bg-purple-50 text-purple-600 rounded-xl text-sm font-black uppercase tracking-wider">
                                        {{ $category->posts_count }} YAZI
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <button class="p-2.5 bg-slate-50 text-slate-400 hover:text-slate-900 rounded-xl transition-all mr-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button class="p-2.5 bg-red-50 text-red-400 hover:bg-red-600 hover:text-white rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-[28px] flex items-center justify-center mx-auto mb-6 text-slate-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-900 mb-1">Kategori Bulunamadı</h3>
                                    <p class="text-slate-500 font-medium">İçeriklerinizi düzenlemek için kategori ekleyin.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
