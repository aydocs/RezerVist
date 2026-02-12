@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] pb-20" x-data="blogPost()">
    <!-- Top Navigation Bar -->
    <div class="sticky top-0 z-[50] bg-white/80 backdrop-blur-xl border-b border-slate-200">
        <div class="max-w-[1600px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.blog.index') }}" class="p-2.5 rounded-xl hover:bg-slate-100 text-slate-500 hover:text-slate-900 transition-all group">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div class="h-8 w-px bg-slate-200"></div>
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-sm font-bold text-slate-500">Çalışma Alanı</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 text-sm font-medium text-slate-500 px-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-text="'Okuma Süresi: ' + readingTime + ' dk'"></span>
                </div>
                <div class="h-8 w-px bg-slate-200"></div>
                <button type="submit" form="createPostForm" class="flex items-center gap-2 px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold transition-all shadow-lg shadow-slate-900/20 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    <span>Yazıyı Yayınla</span>
                </button>
            </div>
        </div>
    </div>

    <form id="createPostForm" action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="max-w-[1600px] mx-auto px-6 py-10">
        @csrf
        <input type="hidden" name="tags" :value="tags.join(',')">
        
        <div class="grid grid-cols-12 gap-10">
            <!-- Left Sidebar (Navigation & Quick Actions) -->
            <div class="col-span-12 lg:col-span-2 hidden lg:block space-y-8">
                <div class="sticky top-32 space-y-2">
                    <p class="px-4 text-xs font-black text-slate-400 uppercase tracking-widest mb-4">BÖLÜMLER</p>
                    <button type="button" @click="scrollTo('section-content')" class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-all" :class="activeSection === 'content' ? 'bg-white text-primary shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'">
                        İçerik Editörü
                    </button>
                    <button type="button" @click="scrollTo('section-seo')" class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-all" :class="activeSection === 'seo' ? 'bg-white text-primary shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'">
                        SEO & Önizleme
                    </button>
                    
                    <div class="pt-8 block">
                        <p class="px-4 text-xs font-black text-slate-400 uppercase tracking-widest mb-4">İPUÇLARI</p>
                        <div class="px-4 space-y-4">
                            <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 text-blue-900">
                                <p class="text-xs font-bold mb-1">💡 Başlık Önerisi</p>
                                <p class="text-[11px] leading-relaxed opacity-80">60 karakterden kısa başlıklar Google'da daha iyi performans gösterir.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content (Center) -->
            <div class="col-span-12 lg:col-span-7 space-y-10">
                
                <!-- Title & Editor Section -->
                <div id="section-content" class="scroll-mt-32">
                    <input 
                        type="text" 
                        name="title" 
                        x-model="title"
                        placeholder="Yazı Başlığı..." 
                        class="w-full bg-transparent border-none p-0 text-5xl font-black text-slate-900 placeholder:text-slate-300 focus:ring-0 leading-tight mb-8"
                    >
                    
                    <div class="bg-white rounded-[24px] shadow-sm border border-slate-200 min-h-[500px]">
                        <textarea name="content" id="blog-editor" class="w-full">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="p-4 bg-red-50 text-red-600 text-sm font-bold border-t border-red-100">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- SEO Section -->
                <div id="section-seo" class="scroll-mt-32 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-900">Arama Motoru Önizlemesi</h3>
                        <span class="text-xs font-bold px-3 py-1 bg-green-50 text-green-700 rounded-full border border-green-100">Canlı Önizleme</span>
                    </div>

                    <!-- Google Snippet Simulator -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow cursor-default">
                        <div class="max-w-[600px]">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-7 h-7 bg-slate-100 rounded-full flex items-center justify-center p-1">
                                    <svg class="w-4 h-4 text-slate-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm text-slate-800 font-medium">Rezervist Blog</span>
                                    <span class="text-xs text-slate-500">{{ request()->root() }}/blog/<span x-text="slugify(title)"></span></span>
                                </div>
                                <div class="ml-auto">
                                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                </div>
                            </div>
                            <h3 class="text-xl text-[#1a0dab] hover:underline cursor-pointer font-medium mb-1 truncate" x-text="metaTitle || title || 'Başlık Yazınız...'"></h3>
                            <p class="text-sm text-[#4d5156] leading-snug" x-text="metaDescription || 'Henüz bir açıklama girmediniz. Google, sayfa içeriğinden otomatik bir özet oluşturacaktır...'">
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">SEO Başlığı</label>
                            <input type="text" name="meta_title" x-model="metaTitle" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold text-slate-700 text-sm" placeholder="Varsayılan: Yazı Başlığı">
                            <div class="flex justify-between text-[10px] font-bold">
                                <span :class="metaTitle.length > 60 ? 'text-red-500' : 'text-slate-400'" x-text="metaTitle.length + '/60'"></span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Meta Açıklama</label>
                            <textarea name="meta_description" x-model="metaDescription" rows="3" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold text-slate-700 text-sm resize-none" placeholder="İçeriğinizi özetleyen kısa bir açıklama..."></textarea>
                            <div class="flex justify-between text-[10px] font-bold">
                                <span :class="metaDescription.length > 160 ? 'text-red-500' : 'text-slate-400'" x-text="metaDescription.length + '/160'"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Sidebar (Settings) -->
            <div class="col-span-12 lg:col-span-3 space-y-6">
                
                <!-- Status & Publish -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wide mb-6">Yayın Ayarları</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between group">
                            <span class="text-sm font-bold text-slate-700">Yayında</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_published" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                        <div class="h-px bg-slate-100"></div>
                        <div class="flex items-center justify-between group">
                            <span class="text-sm font-bold text-slate-700">Öne Çıkan</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wide">Kategori</h3>
                        <button type="button" class="text-xs font-bold text-primary hover:text-primary/80">+ Yeni Ekle</button>
                    </div>
                    <div class="relative">
                        <select name="category_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold text-slate-700 appearance-none text-sm">
                            <option value="">Seçiniz...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-3.5 pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('category_id')
                        <p class="mt-2 text-xs text-red-500 font-bold ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured Image -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wide mb-4">Kapak Görseli</h3>
                    <div class="relative group cursor-pointer w-full aspect-video rounded-xl overflow-hidden bg-slate-50 border-2 border-dashed border-slate-200 hover:border-primary/50 transition-all flex items-center justify-center" @click="$refs.fileInput.click()" @dragover.prevent="$el.classList.add('border-primary')" @dragleave.prevent="$el.classList.remove('border-primary')" @drop.prevent="handleDrop($event);">
                        
                        <input x-ref="fileInput" type="file" name="featured_image" class="hidden" accept="image/*" @change="previewImage">
                        
                        <div x-show="!imageUrl" class="text-center p-4">
                            <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform text-slate-400 group-hover:text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-500">Resmi Buraya Sürükleyin</p>
                            <p class="text-[10px] text-slate-400 mt-1">veya seçmek için tıklayın</p>
                        </div>
                        
                        <img x-show="imageUrl" :src="imageUrl" class="absolute inset-0 w-full h-full object-cover" style="display: none;">
                        <button type="button" x-show="imageUrl" @click.stop="removeImage" class="absolute top-2 right-2 p-1.5 bg-white/90 rounded-full text-red-500 hover:text-red-600 shadow-sm z-10" style="display: none;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Smart Tags -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wide mb-4">Etiketler</h3>
                    
                    <div class="space-y-3">
                        <div class="flex flex-wrap gap-2 min-h-[30px]">
                            <template x-for="(tag, index) in tags" :key="index">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary/10 text-primary text-xs font-bold rounded-lg group">
                                    <span x-text="tag"></span>
                                    <button type="button" @click="removeTag(index)" class="opacity-50 group-hover:opacity-100 hover:text-red-500 transition-opacity">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </span>
                            </template>
                        </div>
                        
                        <input 
                            type="text" 
                            x-model="tagInput"
                            @keydown.enter.prevent="addTag"
                            @keydown.comma.prevent="addTag"
                            @keydown.backspace="handleBackspace"
                            placeholder="Etiket ekle ve Enter'a bas..." 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold text-slate-700 text-xs"
                        >
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<!-- TinyMCE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('blogPost', () => ({
            title: "{{ old('title') }}",
            metaTitle: "{{ old('meta_title') }}",
            metaDescription: "{{ old('meta_description') }}",
            tags: @json(old('tags') ? explode(',', old('tags')) : []),
            tagInput: '',
            imageUrl: null,
            activeSection: 'content',
            readingTime: 0,

            init() {
                // Initialize TinyMCE
                tinymce.init({
                    selector: '#blog-editor',
                    language: 'tr',
                    language_url: '/js/tr.js',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount fullscreen preview',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat | fullscreen preview',
                    menubar: false,
                    content_style: 'body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 16px; color: #334155; line-height: 1.7; padding: 2rem; }',
                    setup: (editor) => {
                        editor.on('change keyup', () => {
                            editor.save();
                            this.calculateReadingTime(editor.getContent({format: 'text'}));
                        });
                    },
                    height: 600,
                    skin: 'oxide',
                    branding: false,
                    statusbar: false,
                    resize: false
                });

                // Scroll spy to update active section
                window.addEventListener('scroll', () => {
                    const sections = ['section-content', 'section-seo'];
                    for (const id of sections) {
                        const el = document.getElementById(id);
                        if (el && window.scrollY >= (el.offsetTop - 200)) {
                            this.activeSection = id.replace('section-', '');
                        }
                    }
                });
            },

            addTag() {
                const tag = this.tagInput.trim();
                if (tag && !this.tags.includes(tag)) {
                    this.tags.push(tag);
                }
                this.tagInput = '';
            },

            removeTag(index) {
                this.tags.splice(index, 1);
            },

            handleBackspace(e) {
                if (this.tagInput === '' && this.tags.length > 0) {
                    this.tags.pop();
                }
            },

            slugify(text) {
                return text
                    .toString()
                    .toLowerCase()
                    .trim()
                    .replace(/\s+/g, '-')     // Replace spaces with -
                    .replace(/[^\w\-]+/g, '') // Remove all non-word chars
                    .replace(/\-\-+/g, '-');  // Replace multiple - with single -
            },

            previewImage(e) {
                const file = e.target.files[0];
                if (file) {
                    this.readFile(file);
                }
            },

            handleDrop(e) {
                e.target.classList.remove('border-primary');
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    this.$refs.fileInput.files = e.dataTransfer.files; // Update input
                    this.readFile(file);
                }
            },

            readFile(file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            removeImage() {
                this.imageUrl = null;
                this.$refs.fileInput.value = '';
            },

            calculateReadingTime(text) {
                const wpm = 200;
                const words = text.trim().split(/\s+/).length;
                this.readingTime = Math.ceil(words / wpm);
            },

            scrollTo(id) {
                const el = document.getElementById(id);
                if (el) {
                    window.scrollTo({
                        top: el.offsetTop - 140, // Offset for sticky header
                        behavior: 'smooth'
                    });
                }
            }
        }));
    });
</script>

<style>
    /* Custom TinyMCE Overrides for Seamless Integration */
    .tox-tinymce {
        border: none !important;
    }
    .tox-editor-header {
        box-shadow: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }
    .tox-toolbar__primary {
        background: transparent !important;
    }
    /* Hide scrollbar for clean left sidebar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: transparent; 
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; 
    }
</style>
@endsection
