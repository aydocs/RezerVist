@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 relative font-sans">
    
    <!-- Abstract Background Pattern -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1200px] h-[800px] bg-primary/5 rounded-full blur-3xl opacity-60 mix-blend-multiply filter"></div>
        <div class="absolute top-0 right-0 w-[800px] h-[600px] bg-indigo-100/40 rounded-full blur-3xl opacity-40 mix-blend-multiply filter"></div>
    </div>

    <!-- Hero Section -->
    <div class="relative z-10 pt-20 pb-16 lg:pt-32 lg:pb-24 text-center px-4">
        <div class="inline-flex items-center justify-center p-1 px-3 mb-6 bg-indigo-50 rounded-full border border-indigo-100 shadow-sm animate-fade-in-up">
            <span class="text-xs font-bold text-primary tracking-wide uppercase">Başvurunuzu Güncelleyin</span>
        </div>
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-slate-900 tracking-tight mb-8 leading-tight">
            Bilgilerinizi <br class="hidden md:block">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-indigo-600 to-purple-600">Yenileyin</span>
        </h1>
        <p class="max-w-2xl mx-auto text-lg md:text-xl text-slate-600 mb-10 leading-relaxed">
            Eksik veya hatalı bilgilerinizi düzelterek başvurunuzu tekrar incelemeye gönderebilirsiniz.
        </p>
    </div>

    <!-- Application Form Container -->
    <div class="relative z-20 max-w-4xl mx-auto px-4 sm:px-6 pb-24">
        <div class="bg-white rounded-[2rem] shadow-2xl shadow-indigo-100 border border-slate-100 overflow-hidden">
            
            <!-- Form Header -->
            <div class="bg-slate-900 px-8 py-10 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-primary opacity-20"></div>
                <div class="relative z-10">
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Başvuruyu Düzenle</h2>
                    <p class="text-indigo-200">Değişiklikleri yapın ve güncelleyin.</p>
                </div>
            </div>

            <form action="{{ route('business.application.update') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-12 space-y-12">
                @csrf
                @method('PUT')

                <!-- Section 1: Basic Info -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-4 mb-6">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-primary font-bold text-lg">1</div>
                        <h3 class="text-xl font-bold text-slate-900">İşletme Bilgileri</h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">İşletme Adı</label>
                            <input type="text" name="business_name" value="{{ old('business_name', $application->business_name) }}" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium placeholder:text-slate-400"
                                   placeholder="Tabela adınızı giriniz">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                            <div class="relative">
                                <select name="category_id" required 
                                        class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium appearance-none cursor-pointer">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $application->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Telefon</label>
                            <input type="tel" name="phone" value="{{ old('phone', $application->phone) }}" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium placeholder:text-slate-400"
                                   placeholder="05XX XXX XX XX">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">E-posta</label>
                            <input type="email" name="email" value="{{ old('email', $application->email) }}" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium placeholder:text-slate-400"
                                   placeholder="iletisim@isletme.com">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Adres</label>
                            <input type="text" name="address" value="{{ old('address', $application->address) }}" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium placeholder:text-slate-400"
                                   placeholder="İlçe, Mahalle ve Cadde bilgisi">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">İşletme Tanıtımı</label>
                            <textarea name="description" rows="3" required
                                      class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium placeholder:text-slate-400 resize-none"
                                      placeholder="Müşterilerinize kendinizi kısaca anlatın...">{{ old('description', $application->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Legal & Docs -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-4 mb-6">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-primary font-bold text-lg">2</div>
                        <h3 class="text-xl font-bold text-slate-900">Yasal Belgeler</h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                         <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Ticaret Sicil No</label>
                            <input type="text" name="trade_registry_no" value="{{ old('trade_registry_no', $application->trade_registry_no) }}" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium"
                                   placeholder="Sicil No">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Vergi Kimlik No</label>
                            <input type="text" name="tax_id" value="{{ old('tax_id', $application->tax_id) }}" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium"
                                   placeholder="Vergi No">
                        </div>
                    </div>

                    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 flex gap-3 text-sm text-blue-800">
                        <svg class="w-5 h-5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Belgeleri güncellemek istiyorsanız yenilerini yükleyiniz. Boş bırakırsanız mevcut belgeler korunacaktır. (Max 5MB PDF)</span>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach(['trade_registry_document' => 'Ticaret Sicil', 'tax_document' => 'Vergi Levhası', 'license_document' => 'Ruhsat', 'id_document' => 'Yetkili Kimlik', 'bank_document' => 'Banka Bilgileri'] as $field => $label)
                            <div class="relative group" x-data="{ fileName: '' }">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">{{ $label }}</label>
                                <div class="relative w-full h-24 rounded-xl border-2 border-dashed transition-all flex flex-col items-center justify-center cursor-pointer overflow-hidden group-hover:shadow-sm"
                                     :class="fileName ? 'border-green-500 bg-green-50' : 'border-slate-300 hover:border-primary hover:bg-indigo-50/30'">
                                    <input type="file" 
                                           name="{{ $field }}" 
                                           accept=".pdf,image/*" 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                           @change="fileName = $event.target.files[0]?.name || ''">
                                    
                                    <template x-if="!fileName">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-6 h-6 text-slate-400 group-hover:text-primary mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span class="text-xs text-slate-500 font-medium group-hover:text-primary">Yeni PDF Seç</span>
                                        </div>
                                    </template>
                                    
                                    <template x-if="fileName">
                                        <div class="flex flex-col items-center px-2 text-center">
                                            <svg class="w-6 h-6 text-green-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span class="text-xs text-green-700 font-semibold truncate w-full" x-text="fileName"></span>
                                        </div>
                                    </template>
                                </div>
                                <div class="mt-1 text-[10px] text-slate-400 text-center">Mevcut belge yüklü</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action -->
                <div class="pt-8 flex flex-col items-center gap-4">
                    <button type="submit" class="w-full md:w-auto px-12 py-4 bg-slate-900 hover:bg-primary text-white font-bold rounded-xl shadow-xl hover:shadow-2xl hover:shadow-primary/30 transform hover:-translate-y-1 transition-all duration-300 text-lg flex items-center justify-center gap-2">
                        <span>Değişiklikleri Kaydet</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    </button>
                    <a href="{{ route('business.application.status') }}" class="text-sm font-bold text-slate-500 hover:text-slate-700 transition">Vazgeç ve Geri Dön</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
