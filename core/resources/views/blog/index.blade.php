@extends('layouts.app')

@section('title', 'Blog - ' . ($globalSettings['site_name'] ?? config('app.name')))
@section('meta_description', 'Rezervasyon dünyasından ipuçları, işletme hikayeleri ve sektör haberleri.')

@section('content')
<div class="bg-white min-h-screen" x-data="{ viewMode: 'grid', sortOpen: false }">
    <!-- Header & Controls -->
    <div class="border-b border-slate-100 sticky top-20 bg-white/80 backdrop-blur-md z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Blog</h1>

                <!-- Controls -->
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <!-- Search -->
                    <div class="relative flex-1 md:w-64 group">
                        <form action="{{ route('blog.index') }}" method="GET">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ara..." class="w-full bg-slate-50 border-none rounded-xl px-4 py-2.5 font-bold text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-primary/20 transition-all">
                            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                        </form>
                    </div>

                    <div class="h-8 w-px bg-slate-100 hidden md:block"></div>

                    <!-- Sort -->
                    <div class="relative">
                        <button @click="sortOpen = !sortOpen" @click.away="sortOpen = false" class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 rounded-xl font-bold text-slate-700 hover:bg-slate-100 transition-colors text-sm whitespace-nowrap">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                            <span>
                                @switch(request('sort'))
                                    @case('popular') Popüler @break
                                    @case('oldest') En Eski @break
                                    @default En Yeni
                                @endswitch
                            </span>
                        </button>
                        <div x-show="sortOpen" class="absolute top-full right-0 mt-2 w-40 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" class="block px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-primary">En Yeni</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" class="block px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-primary">Popüler</a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" class="block px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-primary">En Eski</a>
                        </div>
                    </div>

                    <!-- View Toggle -->
                    <div class="bg-slate-50 p-1 rounded-xl flex items-center hidden sm:flex">
                        <button @click="viewMode = 'grid'" :class="{'bg-white shadow-sm text-primary': viewMode === 'grid', 'text-slate-400 hover:text-slate-600': viewMode !== 'grid'}" class="p-2 rounded-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </button>
                        <button @click="viewMode = 'list'" :class="{'bg-white shadow-sm text-primary': viewMode === 'list', 'text-slate-400 hover:text-slate-600': viewMode !== 'list'}" class="p-2 rounded-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Tags/Categories Row -->
            <div class="flex items-center gap-3 overflow-x-auto mt-6 pb-2 no-scrollbar">
                <a href="{{ route('blog.index') }}" class="px-4 py-1.5 rounded-full text-sm font-bold whitespace-nowrap border {{ !request('category') ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }} transition-all">
                    Tümü
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="px-4 py-1.5 rounded-full text-sm font-bold whitespace-nowrap border {{ request('category') == $category->slug ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }} transition-all">
                        {{ $category->name }}
                        <span class="ml-1 opacity-60 text-xs">{{ $category->posts_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($posts->count() > 0)
            <!-- Grid View -->
            <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                @foreach($posts as $post)
                    <article class="group cursor-pointer flex flex-col h-full" onclick="window.location='{{ route('blog.show', $post->slug) }}'">
                        <div class="relative aspect-[16/10] overflow-hidden rounded-2xl mb-6 bg-slate-100">
                            <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('images/blog-placeholder.jpg') }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                            @if($post->is_featured)
                                <div class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-black text-slate-900 uppercase tracking-widest">
                                    Öne Çıkan
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-3 text-xs font-bold text-slate-400 mb-3">
                            <span class="text-primary">{{ $post->category->name }}</span>
                            <span>•</span>
                            <span>{{ $post->published_at->translatedFormat('d M Y') }}</span>
                            <span class="ml-auto">{{ number_format($post->views) }} okuma</span>
                        </div>

                        <h2 class="text-xl font-black text-slate-900 mb-3 leading-snug group-hover:text-primary transition-colors">
                            {{ $post->title }}
                        </h2>

                        <p class="text-slate-500 text-sm font-medium line-clamp-2 leading-relaxed mb-4 flex-1">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>
                        
                        <div class="flex items-center gap-2">
                             <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-400">
                                {{ substr($post->author->name, 0, 1) }}
                            </div>
                            <span class="text-xs font-bold text-slate-500">{{ $post->author->name }}</span>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- List View -->
            <div x-show="viewMode === 'list'" class="flex flex-col gap-8" style="display: none;">
                @foreach($posts as $post)
                    <article class="group cursor-pointer flex flex-col md:flex-row gap-6 md:gap-8 items-start border-b border-slate-100 pb-8 last:border-0" onclick="window.location='{{ route('blog.show', $post->slug) }}'">
                        <div class="w-full md:w-64 aspect-[4/3] md:aspect-[3/2] overflow-hidden rounded-2xl bg-slate-100 flex-shrink-0">
                            <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('images/blog-placeholder.jpg') }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 text-xs font-bold text-slate-400 mb-2">
                                <span class="text-primary">{{ $post->category->name }}</span>
                                <span>•</span>
                                <span>{{ $post->published_at->translatedFormat('d M Y') }}</span>
                                @if($post->is_featured)
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded text-[10px] uppercase tracking-wide">Öne Çıkan</span>
                                @endif
                            </div>

                            <h2 class="text-2xl font-black text-slate-900 mb-3 leading-snug group-hover:text-primary transition-colors">
                                {{ $post->title }}
                            </h2>

                            <p class="text-slate-500 text-base font-medium line-clamp-2 leading-relaxed mb-4">
                                {{ Str::limit(strip_tags($post->content), 200) }}
                            </p>

                            <div class="flex items-center gap-4 mt-auto">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-400">
                                        {{ substr($post->author->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-500">{{ $post->author->name }}</span>
                                </div>
                                <div class="flex items-center gap-1 text-xs font-bold text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <span>{{ number_format($post->views) }}</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-16 flex justify-center">
                {{ $posts->appends(request()->query())->links() }}
            </div>

        @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4"></path></svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Sonuç Bulunamadı</h3>
                <p class="text-slate-500 font-medium max-w-sm mx-auto">Aradığınız kriterlere uygun yazı bulunmuyor. Filtreleri temizlemeyi deneyin.</p>
                <a href="{{ route('blog.index') }}" class="mt-6 px-6 py-2.5 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition-colors">
                    Filtreleri Temizle
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
