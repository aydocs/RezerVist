@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] pb-20" x-data="blogPost()">
    <!-- Top Navigation Bar -->
    <div class="sticky top-0 z-[50] bg-white/80 backdrop-blur-xl border-b border-slate-200">
        <div class="max-w-[1700px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.blog.index') }}" class="p-2.5 rounded-xl hover:bg-slate-100 text-slate-500 hover:text-slate-900 transition-all group border border-slate-200 shadow-sm bg-white">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="h-8 w-px bg-slate-200"></div>
                <div class="flex flex-col">
                    <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-slate-400 mb-0.5">
                        <a href="{{ route('admin.blog.index') }}" class="hover:text-purple-600">Blog</a>
                        <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                        <span>Yazıyı Düzenle</span>
                    </nav>
                    <h1 class="text-xl font-black text-slate-900 tracking-tight">İçerik Editörü</h1>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="px-4 py-2 bg-slate-50 rounded-lg border border-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-clock text-slate-400 text-xs"></i>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest" x-text="'Okuma: ' + readingTime + ' DK'"></span>
                </div>
                <div class="h-8 w-px bg-slate-200"></div>
                <button type="submit" form="editPostForm" class="flex items-center gap-2 px-6 py-2.5 bg-slate-900 hover:bg-purple-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg shadow-slate-900/10 active:scale-95">
                    <i class="fa-solid fa-save"></i>
                    <span>Değişiklikleri Kaydet</span>
                </button>
            </div>
        </div>
    </div>

    <form id="editPostForm" action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="max-w-[1700px] mx-auto px-6 py-10">
        @csrf
        @method('PUT')
        <input type="hidden" name="tags" :value="tags.join(',')">
        
        <div class="grid grid-cols-12 gap-10">
            <!-- Left Sidebar -->
            <div class="col-span-12 lg:col-span-2 hidden lg:block space-y-8">
                <div class="sticky top-32 space-y-1">
                    <p class="px-4 text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Navigasyon</p>
                    <button type="button" @click="scrollTo('section-content')" class="w-full text-left px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="activeSection === 'content' ? 'bg-white text-purple-600 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'">
                        Editör
                    </button>
                    <button type="button" @click="scrollTo('section-seo')" class="w-full text-left px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" :class="activeSection === 'seo' ? 'bg-white text-purple-600 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'">
                        SEO & Önizleme
                    </button>
                    
                    <div class="pt-8 block">
                        <button type="button" id="ai-translate-btn" class="w-full px-4 py-3 bg-purple-50 text-purple-600 rounded-xl text-[9px] font-black uppercase tracking-widest border border-purple-100 hover:bg-purple-100 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            AI Çeviri
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-span-12 lg:col-span-7 space-y-10">
                <div id="section-content" class="scroll-mt-32">
                    <input 
                        type="text" 
                        name="title" 
                        x-model="title"
                        placeholder="Yazı Başlığı..." 
                        class="w-full bg-transparent border-none p-0 text-5xl font-black text-slate-900 placeholder:text-slate-200 focus:ring-0 leading-tight mb-8 tracking-tighter"
                    >
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 min-h-[500px] overflow-hidden">
                        <textarea name="content" id="blog-editor" class="w-full">{{ $post->content }}</textarea>
                        @error('content')
                            <div class="p-4 bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-widest border-t border-rose-100">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div id="section-seo" class="scroll-mt-32 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Arama Motoru (SEO)</h3>
                        <span class="text-[9px] font-black px-3 py-1 bg-emerald-50 text-emerald-600 rounded border border-emerald-100 uppercase tracking-widest">Canlı Simülatör</span>
                    </div>

                    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="max-w-[600px]">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-6 h-6 bg-slate-50 rounded-full flex items-center justify-center p-1 border border-slate-100">
                                    <i class="fa-solid fa-globe text-slate-400 text-[10px]"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-slate-800 font-bold">RezerVist Blog</span>
                                    <span class="text-[10px] text-slate-400">{{ request()->root() }}/blog/<span x-text="slugify(title)" class="text-purple-600"></span></span>
                                </div>
                            </div>
                            <h3 class="text-lg text-[#1a0dab] hover:underline cursor-pointer font-bold mb-1 truncate" x-text="metaTitle || title || 'Başlık Yazınız...'"></h3>
                            <p class="text-[13px] text-[#4d5156] leading-relaxed" x-text="metaDescription || 'Henüz bir açıklama girmediniz. Google, sayfa içeriğinden otomatik bir özet oluşturacaktır...'">
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">SEO Başlığı</label>
                            <input type="text" name="meta_title" x-model="metaTitle" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-bold text-slate-700 text-xs" placeholder="Varsayılan: Yazı Başlığı">
                            <div class="flex justify-end pr-1 text-[9px] font-black uppercase tracking-widest">
                                <span :class="metaTitle.length > 60 ? 'text-rose-500' : 'text-slate-400'" x-text="metaTitle.length + '/60'"></span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Meta Açıklama</label>
                            <textarea name="meta_description" x-model="metaDescription" rows="3" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-bold text-slate-700 text-xs resize-none" placeholder="İçeriğinizi özetleyen kısa bir açıklama..."></textarea>
                            <div class="flex justify-end pr-1 text-[9px] font-black uppercase tracking-widest">
                                <span :class="metaDescription.length > 160 ? 'text-rose-500' : 'text-slate-400'" x-text="metaDescription.length + '/160'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-span-12 lg:col-span-3 space-y-6">
                <!-- Status & Publish -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] mb-6">YAYIN AYARLARI</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-black text-slate-700 uppercase tracking-widest">Yayın Durumu</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ $post->is_published ? 'checked' : '' }}>
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                        <div class="h-px bg-slate-100"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-black text-slate-700 uppercase tracking-widest">Öne Çıkarılan</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" class="sr-only peer" {{ $post->is_featured ? 'checked' : '' }}>
                                <div class="w-10 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em]">KATEGORİ</h3>
                    </div>
                    <div class="relative">
                        <select name="category_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-bold text-slate-700 appearance-none text-xs">
                            <option value="">Seçiniz...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-3.5 pointer-events-none text-slate-400 text-[10px]"></i>
                    </div>
                    @error('category_id')
                        <p class="mt-2 text-[9px] text-rose-500 font-black uppercase tracking-widest ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured Image -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] mb-4">KAPAK GÖRSELİ</h3>
                    <div class="relative group cursor-pointer w-full aspect-video rounded-xl overflow-hidden bg-slate-50 border-2 border-dashed border-slate-200 hover:border-purple-300 transition-all flex items-center justify-center" @click="$refs.fileInput.click()" @dragover.prevent @dragleave.prevent @drop.prevent="handleDrop($event);">
                        <input x-ref="fileInput" type="file" name="featured_image" class="hidden" accept="image/*" @change="previewImage">
                        
                        <div x-show="!imageUrl" class="text-center p-4">
                            <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform text-slate-300 group-hover:text-purple-600 border border-slate-100">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Görsel Seçin</p>
                        </div>
                        
                        <img x-show="imageUrl" :src="imageUrl" class="absolute inset-0 w-full h-full object-cover">
                        <button type="button" x-show="imageUrl" @click.stop="removeImage" class="absolute top-2 right-2 p-1.5 bg-white/90 rounded-lg text-rose-500 hover:text-rose-600 shadow-sm z-10 border border-slate-200">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <!-- Smart Tags -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] mb-4">ETİKETLER</h3>
                    <div class="space-y-3">
                        <div class="flex flex-wrap gap-2 min-h-[30px]">
                            <template x-for="(tag, index) in tags" :key="index">
                                <span class="inline-flex items-center gap-2 px-2.5 py-1 bg-purple-50 text-purple-600 text-[10px] font-black rounded uppercase tracking-widest border border-purple-100 group">
                                    <span x-text="tag"></span>
                                    <button type="button" @click="removeTag(index)" class="opacity-50 group-hover:opacity-100 hover:text-rose-600 transition-opacity">
                                        <i class="fa-solid fa-xmark"></i>
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
                            placeholder="Yeni ekle..." 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-bold text-slate-700 text-xs"
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
            title: "{{ $post->title }}",
            metaTitle: "{{ $post->meta_title }}",
            metaDescription: "{{ $post->meta_description }}",
            tags: @json($post->tags ? explode(',', $post->tags) : []),
            tagInput: '',
            imageUrl: "{{ $post->featured_image ? asset('storage/' . $post->featured_image) : null }}",
            activeSection: 'content',
            readingTime: 0,

            init() {
                tinymce.init({
                    selector: '#blog-editor',
                    language: 'tr',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount fullscreen preview',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat | fullscreen preview',
                    menubar: false,
                    content_style: 'body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 16px; color: #334155; line-height: 1.7; padding: 2rem; }',
                    setup: (editor) => {
                        editor.on('change keyup', () => {
                            editor.save();
                            this.calculateReadingTime(editor.getContent({format: 'text'}));
                        });
                        editor.on('init', () => {
                            this.calculateReadingTime(editor.getContent({format: 'text'}));
                        });
                    },
                    height: 600,
                    skin: 'oxide',
                    branding: false,
                    statusbar: false,
                    resize: false
                });

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
                return text.toString().toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '').replace(/\-\-+/g, '-');
            },

            previewImage(e) {
                const file = e.target.files[0];
                if (file) this.readFile(file);
            },

            handleDrop(e) {
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    this.$refs.fileInput.files = e.dataTransfer.files;
                    this.readFile(file);
                }
            },

            readFile(file) {
                const reader = new FileReader();
                reader.onload = (e) => this.imageUrl = e.target.result;
                reader.readAsDataURL(file);
            },

            removeImage() {
                this.imageUrl = null;
                this.$refs.fileInput.value = '';
            },

            calculateReadingTime(text) {
                const words = text.trim().split(/\s+/).length;
                this.readingTime = Math.ceil(words / 200);
            },

            scrollTo(id) {
                const el = document.getElementById(id);
                if (el) window.scrollTo({ top: el.offsetTop - 140, behavior: 'smooth' });
            }
        }));

        document.getElementById('ai-translate-btn').addEventListener('click', function() {
            const btn = this;
            const originalHTML = btn.innerHTML;
            
            Swal.fire({
                title: 'AI Çeviri Başlatılıyor',
                text: 'Bu yazı tüm aktif dillere profesyonelce çevrilecek. Onaylıyor musunuz?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Evet, Çevir',
                cancelButtonText: 'Vazgeç',
                confirmButtonColor: '#7C3AED',
                cancelButtonColor: '#64748b',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-black text-[10px] uppercase tracking-widest px-6 py-3',
                    cancelButton: 'rounded-xl font-black text-[10px] uppercase tracking-widest px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> İşleniyor...';
                    
                    fetch('{{ route('admin.ai.translate-model') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            model_type: 'Post',
                            model_id: {{ $post->id }}
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Başarılı!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#7C3AED',
                                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-black text-[10px] uppercase tracking-widest px-6 py-3' }
                            }).then(() => window.location.reload());
                        } else {
                            Swal.fire({
                                title: 'Hata!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonColor: '#7C3AED',
                                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-black text-[10px] uppercase tracking-widest px-6 py-3' }
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            title: 'Hata!',
                            text: 'Bir sorun oluştu.',
                            icon: 'error',
                            confirmButtonColor: '#7C3AED',
                            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-black text-[10px] uppercase tracking-widest px-6 py-3' }
                        });
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                    });
                }
            });
        });
    });
</script>

<style>
    .tox-tinymce { border: none !important; }
    .tox-editor-header { box-shadow: none !important; border-bottom: 1px solid #f1f5f9 !important; }
    .tox-toolbar__primary { background: transparent !important; }
</style>
@endsection
