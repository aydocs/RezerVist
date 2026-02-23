@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1700px] mx-auto space-y-8">
        
        <!-- Header & Top Nav -->
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b pb-6 border-slate-200">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Yönetim</a>
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    <span>İçerik Stratejisi</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Blog <span class="text-purple-600">Yönetimi</span></h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.blog.categories') }}" class="px-5 py-2.5 bg-white text-slate-700 text-[10px] font-black rounded-xl border border-slate-200 hover:border-purple-200 hover:text-purple-600 transition-all uppercase tracking-widest flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-tags"></i>
                    Kategoriler
                </a>
                <a href="{{ route('admin.blog.create') }}" class="px-5 py-2.5 bg-slate-900 text-white text-[10px] font-black rounded-xl hover:bg-purple-600 transition-all uppercase tracking-widest flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-plus"></i>
                    Yeni Yazı
                </a>
            </div>
        </div>

        <!-- Density Optimized Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">İçerik Detayı</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Durum</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Yayın Tarihi</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksiyonlar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($posts as $post)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-10 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200 grayscale group-hover:grayscale-0 transition-all duration-500">
                                    @if($post->featured_image)
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <i class="fa-solid fa-image text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-black text-slate-900 line-clamp-1 truncate max-w-sm group-hover:text-purple-600 transition-colors">{{ $post->title }}</div>
                                    <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Yazar: {{ $post->author->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-[9px] font-black uppercase tracking-wider border border-slate-200">
                                {{ $post->category->name }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($post->is_published)
                                <div class="inline-flex items-center gap-1.5 text-emerald-600 bg-emerald-50 px-3 py-1 bg-opacity-50 rounded-lg text-[9px] font-black border border-emerald-100 uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Yayında
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 text-slate-400 bg-slate-50 px-3 py-1 rounded-lg text-[9px] font-black border border-slate-100 uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                    Taslak
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-[11px] font-black text-slate-700">{{ $post->published_at ? $post->published_at->format('d.m.Y') : '-' }}</div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $post->published_at ? $post->published_at->format('H:i') : '' }}</div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="w-8 h-8 flex items-center justify-center border border-slate-200 text-slate-400 hover:text-purple-600 hover:border-purple-100 transition-all rounded-lg" title="Görüntüle">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                                <a href="{{ route('admin.blog.edit', $post) }}" class="w-8 h-8 flex items-center justify-center border border-slate-200 text-slate-400 hover:text-purple-600 hover:border-purple-100 transition-all rounded-lg" title="Düzenle">
                                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                </a>
                                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Silinsin mi?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center border border-slate-200 text-slate-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all rounded-lg" title="Sil">
                                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center opacity-40">
                            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-pen-nib text-3xl text-slate-300"></i>
                            </div>
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">İçerik Bulunamadı</h3>
                            <p class="text-[10px] text-slate-500 font-bold mt-1">Platformda henüz bir blog yazısı bulunmuyor.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
        <div class="flex justify-center">
            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
