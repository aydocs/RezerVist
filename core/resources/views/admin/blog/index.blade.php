@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 pt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Blog Yönetimi</h1>
                <p class="text-slate-500 font-medium font-outfit">İçeriklerinizi ve kategorilerinizi buradan yönetin</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.blog.categories') }}" class="px-6 py-3 bg-white text-slate-700 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Kategoriler
                </a>
                <a href="{{ route('admin.blog.create') }}" class="px-6 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Yeni Yazı Ekle
                </a>
            </div>
        </div>

        <!-- Posts Table -->
        <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Yazı</th>
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Durum</th>
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Tarih</th>
                            <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($posts as $post)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-12 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                                        @if($post->featured_image)
                                            <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-black text-slate-900 line-clamp-1 truncate max-w-xs group-hover:text-primary transition-colors">{{ $post->title }}</div>
                                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $post->author->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-black uppercase tracking-wider">
                                    {{ $post->category->name }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                @if($post->is_published)
                                    <span class="inline-flex items-center gap-1.5 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Yayında
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-slate-400 bg-slate-50 px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider">
                                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                        Taslak
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-slate-600">{{ $post->published_at ? $post->published_at->format('d.m.Y') : '-' }}</div>
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $post->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="p-2.5 bg-slate-50 text-slate-400 hover:text-slate-900 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.blog.edit', $post) }}" class="p-2.5 bg-blue-50 text-blue-400 hover:bg-blue-600 hover:text-white rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Bu yazıyı silmek istediğinize emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-red-50 text-red-400 hover:bg-red-600 hover:text-white rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-[28px] flex items-center justify-center mx-auto mb-6 text-slate-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 mb-1">Yazı Bulunamadı</h3>
                                <p class="text-slate-500 font-medium">İlk blog yazınızı yazarak platformu canlandırın!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($posts->hasPages())
            <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
                {{ $posts->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
