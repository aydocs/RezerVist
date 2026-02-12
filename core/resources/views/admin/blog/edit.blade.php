@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 pt-28">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.blog.index') }}" class="p-3 bg-white rounded-2xl border border-slate-200 text-slate-600 hover:text-primary transition-all shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-1">Yazıyı Düzenle</h1>
                <p class="text-slate-500 font-medium font-outfit">İçeriğinizi güncelleyin ve mükemmelleştirin</p>
            </div>
        </div>

        <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-black text-slate-400 uppercase tracking-widest mb-3">Yazı Başlığı</label>
                                <input type="text" name="title" value="{{ $post->title }}" required class="w-full px-6 py-4 bg-slate-50 border @error('title') border-red-500 @else border-slate-100 @enderror rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-black text-2xl text-slate-900 placeholder:text-slate-300">
                                @error('title')
                                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-black text-slate-400 uppercase tracking-widest mb-3">İçerik</label>
                                <textarea name="content" id="blog-editor" class="hidden">{{ $post->content }}</textarea>
                                <div id="editor-container" class="min-h-[400px] border @error('content') border-red-500 @else border-slate-100 @enderror rounded-[32px] p-6 bg-slate-50">
                                    {!! $post->content !!}
                                </div>
                                @error('content')
                                    <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SEO Section -->
                    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100">
                        <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
                             <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                             SEO Ayarları
                        </h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-black text-slate-400 uppercase tracking-widest mb-2">Meta Başlık</label>
                                <input type="text" name="meta_title" value="{{ $post->meta_title }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-slate-900">
                            </div>
                            <div>
                                <label class="block text-sm font-black text-slate-400 uppercase tracking-widest mb-2">Meta Açıklama</label>
                                <textarea name="meta_description" rows="3" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-slate-900">{{ $post->meta_description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Settings -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Publish Box -->
                    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100">
                        <h3 class="text-lg font-black text-slate-900 mb-6">Geri Dön</h3>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <span class="font-bold text-slate-700">Durum: {{ $post->is_published ? 'Yayında' : 'Taslak' }}</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ $post->is_published ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>
                            

                            
                            <button type="submit" class="w-full py-4 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all uppercase tracking-widest text-sm">
                                Değişiklikleri Kaydet
                            </button>
                        </div>
                    </div>

                    <script>
                        document.getElementById('ai-translate-btn').addEventListener('click', function() {
                            const btn = this;
                            const originalHTML = btn.innerHTML;
                            
                            Swal.fire({
                                title: 'AI Çeviri Başlatılıyor',
                                text: 'Bu yazı tüm aktif dillere (EN, RU, DE, AR) profesyonelce çevrilecek. Onaylıyor musunuz?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Evet, Çevir',
                                cancelButtonText: 'Vazgeç',
                                confirmButtonColor: '#10b981'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    btn.disabled = true;
                                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Çeviriliyor...';
                                    
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
                                            Swal.fire('Başarılı!', data.message, 'success').then(() => {
                                                window.location.reload();
                                            });
                                        } else {
                                            Swal.fire('Hata!', data.message, 'error');
                                        }
                                    })
                                    .catch(error => {
                                        Swal.fire('Hata!', 'Bir sorun oluştu.', 'error');
                                    })
                                    .finally(() => {
                                        btn.disabled = false;
                                        btn.innerHTML = originalHTML;
                                    });
                                }
                            });
                        });
                    </script>

                    <!-- Category Box -->
                    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100">
                        <h3 class="text-lg font-black text-slate-900 mb-6">Kategori</h3>
                        <select name="category_id" required class="w-full px-5 py-4 bg-slate-50 border @error('category_id') border-red-500 @else border-slate-100 @enderror rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-slate-900">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Box -->
                    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100">
                        <h3 class="text-lg font-black text-slate-900 mb-6">Kapak Resmi</h3>
                        <div class="relative group cursor-pointer" onclick="document.getElementById('featured_image').click()">
                            <input type="file" name="featured_image" id="featured_image" class="hidden" accept="image/*" onchange="previewImage(this)">
                            <div id="image-preview" class="w-full aspect-[4/3] bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 group-hover:bg-slate-100 group-hover:border-primary/30 transition-all overflow-hidden {{ $post->featured_image ? 'opacity-0' : '' }}">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-xs font-black uppercase tracking-widest">Resmi Değiştir</span>
                            </div>
                            <img id="preview-img" src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : '' }}" class="{{ $post->featured_image ? '' : 'hidden' }} w-full h-full object-cover rounded-2xl absolute inset-0">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      target: '#editor-container',
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
      setup: function (editor) {
        editor.on('change', function () {
            editor.save();
            document.getElementById('blog-editor').value = editor.getContent();
        });
      },
      height: 400,
      border_radius: '32px'
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-img').classList.remove('hidden');
                document.getElementById('image-preview').classList.add('opacity-0');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<style>
    .tox-tinymce {
        border: none !important;
        border-radius: 32px !important;
        background: transparent !important;
    }
</style>
@endsection
