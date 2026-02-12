@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title . ' - Rezervist Blog')
@section('meta_description', $post->meta_description ?: Str::limit(strip_tags($post->content), 160))
@section('meta_image', $post->featured_image ? asset('storage/' . $post->featured_image) : null)

@section('content')
<div class="bg-white min-h-screen pt-32 pb-20">
    <article class="max-w-3xl mx-auto px-6">
        <!-- Header -->
        <header class="mb-12 text-center">
            <div class="flex items-center justify-center gap-2 text-sm font-bold text-slate-500 mb-6">
                <a href="{{ route('blog.index') }}" class="hover:text-primary transition-colors">Blog</a>
                <span>/</span>
                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="text-primary">{{ $post->category->name }}</a>
            </div>

            <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight mb-8 tracking-tight">
                {{ $post->title }}
            </h1>

            <div class="flex items-center justify-center gap-6 border-y border-slate-100 py-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-400">
                        {{ substr($post->author->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-black text-slate-900 text-sm">{{ $post->author->name }}</div>
                        <div class="text-xs font-bold text-slate-400">{{ $post->published_at->translatedFormat('d F Y') }}</div>
                    </div>
                </div>
                <div class="w-px h-8 bg-slate-100"></div>
                <div class="text-xs font-bold text-slate-400">
                    {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} dk okuma
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        @if($post->featured_image)
        <div class="mb-12 rounded-2xl overflow-hidden shadow-sm bg-slate-50">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto">
        </div>
        @endif

        <!-- Content -->
        <div class="prose prose-lg prose-slate max-w-none 
                    prose-headings:font-black prose-headings:text-slate-900 
                    prose-p:text-slate-600 prose-p:leading-relaxed prose-p:font-medium
                    prose-a:text-primary prose-a:font-bold prose-a:no-underline hover:prose-a:underline
                    prose-img:rounded-xl">
            {!! $post->content !!}
        </div>

        <!-- Tags -->
        @if(!empty($post->tags))
        <div class="mt-12 flex flex-wrap gap-2">
            @foreach($post->tags as $tag)
                <a href="{{ route('blog.index', ['search' => $tag]) }}" class="px-3 py-1 bg-slate-50 rounded-lg text-xs font-bold text-slate-500 hover:bg-slate-100 transition-colors">
                    #{{ $tag }}
                </a>
            @endforeach
        </div>
        @endif

        <!-- Comments Section -->
        <section class="mt-20 pt-12 border-t border-slate-100" id="comments">
            <h3 class="text-2xl font-black text-slate-900 mb-8 flex items-center gap-2">
                Yorumlar <span class="text-slate-300 text-lg">({{ $post->comments->count() }})</span>
            </h3>

            @auth
                <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-12 bg-slate-50 p-6 rounded-2xl">
                    @csrf
                    <div class="mb-4">
                        <label for="content" class="sr-only">Yorumunuz</label>
                        <textarea name="content" id="content" rows="3" class="w-full bg-white border-0 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 placeholder:text-slate-400 font-medium text-slate-900 resize-none" placeholder="Düşüncelerinizi paylaşın..."></textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-colors">
                            Yorum Yap
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-slate-50 p-8 rounded-2xl text-center mb-12 border border-slate-100">
                    <p class="text-slate-600 font-bold mb-4">Yorum yapmak için giriş yapmalısınız.</p>
                    <a href="{{ route('login') }}" class="inline-block px-6 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-colors">
                        Giriş Yap
                    </a>
                </div>
            @endauth

            <div class="space-y-8">
                @foreach($post->comments as $comment)
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-400 flex-shrink-0">
                            {{ substr($comment->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="bg-slate-50 rounded-2xl p-5 relative group">
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="font-black text-slate-900 text-sm">{{ $comment->user->name }}</h5>
                                    <span class="text-xs font-bold text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-slate-600 font-medium text-sm leading-relaxed">{{ $comment->content }}</p>

                                @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->isAdmin()))
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600" onclick="return confirm('Bu yorumu silmek istediğinize emin misiniz?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($post->comments->isEmpty())
                    <p class="text-center text-slate-400 font-medium italic py-8">Henüz yorum yapılmamış. İlk yorumu siz yapın!</p>
                @endif
            </div>
        </section>
    </article>
</div>
@endsection
