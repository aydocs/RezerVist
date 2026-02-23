@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12" x-data="categoryManager()">
    <div class="max-w-[1700px] mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex items-center gap-6 border-b pb-6 border-slate-200">
            <a href="{{ route('admin.blog.index') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-xl border border-slate-200 text-slate-400 hover:text-purple-600 transition-all shadow-sm">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Yönetim</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <a href="{{ route('admin.blog.index') }}" class="hover:text-purple-600 transition-colors">Blog</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Sınıflandırma</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Kategori <span class="text-purple-600">Yönetimi</span></h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Add Category Form -->
            <div class="lg:col-span-4 lg:sticky lg:top-8">
                <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle text-purple-600"></i>
                        Yeni Kategori
                    </h3>
                    <form action="{{ route('admin.blog.categories.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori Adı</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-bold text-slate-900 placeholder:text-slate-300 text-xs outline-none" placeholder="Örn: Teknoloji Trendleri">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Açıklama</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-bold text-slate-900 placeholder:text-slate-300 text-xs outline-none resize-none" placeholder="Kısa bir açıklama..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-slate-900 text-white font-black rounded-xl hover:bg-purple-600 transition-all uppercase tracking-widest text-[10px] shadow-lg shadow-slate-900/10">
                            Kategoriyi Kaydet
                        </button>
                    </form>
                </div>
            </div>

            <!-- Categories Table -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Kategori & Detay</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Yazı Sayısı</th>
                                <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Aksiyon</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="font-black text-slate-900 text-sm group-hover:text-purple-600 transition-colors uppercase tracking-tight">{{ $category->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 line-clamp-1 truncate max-w-md">{{ $category->description ?: 'Tanımlama yapılmamış.' }}</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="inline-flex items-center px-4 py-1.5 bg-purple-50 text-purple-600 rounded-lg text-[9px] font-black border border-purple-100 uppercase tracking-widest">
                                        {{ $category->posts_count }} YAYIN
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button @click="editCategory(@json($category))" class="w-8 h-8 flex items-center justify-center border border-slate-200 text-slate-400 hover:text-purple-600 hover:border-purple-100 transition-all rounded-lg" title="Düzenle">
                                            <i class="fa-solid fa-pen text-[10px]"></i>
                                        </button>
                                        <form action="{{ route('admin.blog.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Silinsin mi?')">
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
                                <td colspan="3" class="px-8 py-20 text-center opacity-30">
                                    <i class="fa-solid fa-folder-open text-4xl mb-4 text-slate-300"></i>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Henüz Kategori Yok</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="editing" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" x-cloak x-transition>
        <div class="bg-white rounded-[32px] w-full max-w-md p-8 shadow-2xl border border-slate-100" @click.away="editing = false">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Kategoriyi <span class="text-purple-600">Güncelle</span></h3>
                <button @click="editing = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form :action="'{{ route('admin.blog.categories.update', '') }}/' + category.id" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori Adı</label>
                    <input type="text" name="name" x-model="category.name" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-bold text-slate-900 text-sm outline-none">
                </div>
                <div class="space-y-2">
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Açıklama</label>
                    <textarea name="description" x-model="category.description" rows="4" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-bold text-slate-900 text-sm outline-none resize-none"></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl hover:bg-purple-600 transition-all uppercase tracking-widest text-xs shadow-xl shadow-slate-900/10">
                    Değişiklikleri Kaydet
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function categoryManager() {
        return {
            editing: false,
            category: {},
            editCategory(cat) {
                this.category = { ...cat };
                this.editing = true;
            }
        }
    }
</script>
@endsection
