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
            <span class="text-xs font-bold text-primary tracking-wide uppercase">İş Ortağımız Olun</span>
        </div>
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-slate-900 tracking-tight mb-8 leading-tight">
            İşletmenizi <br class="hidden md:block">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-indigo-600 to-purple-600">Dijitale Taşıyın</span>
        </h1>
        <p class="max-w-2xl mx-auto text-lg md:text-xl text-slate-600 mb-10 leading-relaxed">
            Rezervist ile binlerce yeni müşteriye ulaşın, rezervasyon süreçlerinizi otomatikleştirin ve gelirinizi artırın. Şimdi başvurun.
        </p>
    </div>

    <!-- Application Form Container -->
    <div class="relative z-20 max-w-4xl mx-auto px-4 sm:px-6 pb-24">
        <div class="bg-white rounded-[2rem] shadow-2xl shadow-indigo-100 border border-slate-100 overflow-hidden">
            
            <!-- Form Header -->
            <div class="bg-slate-900 px-8 py-10 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-primary opacity-20"></div>
                <div class="relative z-10">
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Başvuru Formu</h2>
                    <p class="text-indigo-200">Formu doldurun, işletmenizi hemen yayınlayalım.</p>
                </div>
            </div>

            <form action="{{ route('business.apply.submit') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-12 space-y-12">
                @csrf

                <!-- Section 1: Basic Info -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-4 mb-6">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-primary font-bold text-lg">1</div>
                        <h3 class="text-xl font-bold text-slate-900">İşletme Bilgileri</h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">İşletme Adı</label>
                            <input type="text" name="business_name" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium placeholder:text-slate-400"
                                   placeholder="Tabela adınızı giriniz">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                            <div class="relative">
                                <select name="category_id" required 
                                        class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium appearance-none cursor-pointer">
                                    <option value="" disabled selected>Kategori Seçiniz</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Telefon</label>
                            <input type="tel" name="phone" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium placeholder:text-slate-400"
                                   placeholder="05XX XXX XX XX">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">E-posta</label>
                            <input type="email" name="email" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium placeholder:text-slate-400"
                                   placeholder="iletisim@isletme.com">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Adres</label>
                            <input type="text" name="address" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium placeholder:text-slate-400"
                                   placeholder="İlçe, Mahalle ve Cadde bilgisi">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">İşletme Tanıtımı</label>
                            <textarea name="description" rows="3" required
                                      class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium placeholder:text-slate-400 resize-none"
                                      placeholder="Müşterilerinize kendinizi kısaca anlatın..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Features -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-4 mb-6">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-primary font-bold text-lg">2</div>
                        <h3 class="text-xl font-bold text-slate-900">İşletme Özellikleri</h3>
                    </div>

                    @if(isset($tags) && count($tags) > 0)
                        @foreach($tags as $groupName => $groupTags)
                            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">{{ $groupName }}</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @foreach($groupTags as $tag)
                                        <label class="cursor-pointer group relative">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="peer sr-only">
                                            <div class="py-2.5 px-3 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 transition-all duration-200 peer-checked:border-primary peer-checked:text-primary peer-checked:bg-indigo-50 hover:border-indigo-300 text-center flex items-center justify-center h-full">
                                                {{ $tag->name }}
                                            </div>
                                            <!-- Check Icon -->
                                            <div class="absolute -top-2 -right-2 bg-primary text-white rounded-full p-0.5 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 shadow-md">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                         <p class="text-slate-500 italic">Özellikler yüklenemedi.</p>
                    @endif
                </div>

                <!-- Section 3: Legal & Docs -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 border-b border-slate-100 pb-4 mb-6">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-primary font-bold text-lg">3</div>
                        <h3 class="text-xl font-bold text-slate-900">Yasal Belgeler</h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                         <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Ticaret Sicil No</label>
                            <input type="text" name="trade_registry_no" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium"
                                   placeholder="Sicil No">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Vergi Kimlik No</label>
                            <input type="text" name="tax_id" required 
                                   class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 outline-none font-medium"
                                   placeholder="Vergi No">
                        </div>
                    </div>

                    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 flex gap-3 text-sm text-blue-800">
                        <svg class="w-5 h-5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Lütfen belgeleri okunaklı bir PDF formatında yükleyiniz. (Max 5MB)</span>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach(['trade_registry_document' => 'Ticaret Sicil', 'tax_document' => 'Vergi Levhası', 'license_document' => 'Ruhsat', 'id_document' => 'Yetkili Kimlik', 'bank_document' => 'Banka Bilgileri'] as $field => $label)
                            <div class="relative group" x-data="{ fileName: '' }">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">{{ $label }}</label>
                                <div class="relative w-full h-24 rounded-xl border-2 border-dashed transition-all flex flex-col items-center justify-center cursor-pointer overflow-hidden group-hover:shadow-sm"
                                     :class="fileName ? 'border-green-500 bg-green-50' : 'border-slate-300 hover:border-primary hover:bg-indigo-50/30'">
                                    <input type="file" 
                                           name="{{ $field }}" 
                                           accept=".pdf" 
                                           required 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                           @change="fileName = $event.target.files[0]?.name || ''">
                                    
                                    <template x-if="!fileName">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-6 h-6 text-slate-400 group-hover:text-primary mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span class="text-xs text-slate-500 font-medium group-hover:text-primary">PDF Seç</span>
                                        </div>
                                    </template>
                                    
                                    <template x-if="fileName">
                                        <div class="flex flex-col items-center px-2 text-center">
                                            <svg class="w-6 h-6 text-green-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span class="text-xs text-green-700 font-semibold truncate w-full" x-text="fileName"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Terms -->
                <div class="pt-6">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <div class="relative flex items-center mt-0.5">
                            <input type="checkbox" name="terms_accepted" required class="peer sr-only">
                            <div class="w-5 h-5 border-2 border-slate-300 rounded peer-checked:bg-primary peer-checked:border-primary transition-all"></div>
                            <svg class="absolute w-3 h-3 text-white left-1 bottom-1 opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="text-sm text-slate-600 leading-relaxed">
                            <a href="/contracts#partnership" target="_blank" class="text-primary font-bold hover:underline">İş Ortaklığı Sözleşmesi</a>'ni ve 
                            <a href="/contracts#terms" target="_blank" class="text-primary font-bold hover:underline">Hizmet Koşulları</a>'nı okudum. İşletme yetkilisi olduğumu ve verdiğim bilgilerin doğruluğunu beyan ederim.
                        </div>
                    </label>
                </div>

                <!-- Action -->
                <div class="pt-8 flex flex-col items-center">
                    <button type="submit" class="w-full md:w-auto px-12 py-4 bg-slate-900 hover:bg-primary text-white font-bold rounded-xl shadow-xl hover:shadow-2xl hover:shadow-primary/30 transform hover:-translate-y-1 transition-all duration-300 text-lg flex items-center justify-center gap-2">
                        <span>Başvuruyu Gönder</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    <p class="mt-4 text-xs text-slate-400">Başvurunuz incelendikten sonra size e-posta ile dönüş yapılacaktır.</p>
                </div>
            </form>
        </div>
    </div>

    <!-- Features / Why Join -->
    <div class="bg-white py-24 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Neden Rezervist?</h2>
                <p class="text-slate-600">İşletmenizi modern çağın gerekliliklerine taşıyın, operasyonel yükünüzü hafifletin.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Zaman Tasarrufu</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Telefon trafiğini azaltın. Müşterileriniz 7/24 dilediği zaman rezervasyon yapsın, siz işinize odaklanın.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Gelir Artışı</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Boş masalarınızı doldurun. Gelişmiş pazarlama araçlarımızla daha fazla müşteriye ulaşın.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-purple-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Kolay Yönetim</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Kullanıcı dostu panelimizden menünüzü, rezervasyonlarınızı ve personelini kolayca yönetin.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-slate-50 py-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Sıkça Sorulan Sorular</h2>
                <p class="text-slate-500">Aklınıza takılan soruların cevaplarını burada bulabilirsiniz.</p>
            </div>
            
            <div class="space-y-4" x-data="{ active: null }">
                @foreach([
                    ['q' => 'Başvuru ücretli mi?', 'a' => 'Hayır, Rezervist\'e katılmak için herhangi bir giriş veya başvuru ücreti ödemezsiniz. İlk 3 ay boyunca tüm özelliklerden tamamen ücretsiz yararlanabilirsiniz.'],
                    ['q' => 'Ödemeler ne zaman hesabıma geçer?', 'a' => 'İşletmenizde gerçekleşen rezervasyonların ödemeleri, operasyonun tamamlanmasını takip eden ilk iş gününde (Ertesi Gün Ödeme) banka hesabınıza otomatik olarak aktarılır.'],
                    ['q' => 'Hangi belgeler gerekli?', 'a' => 'Yasal olarak faaliyet gösterebilmeniz için Vergi Levhası, Ticaret Sicil Gazetesi (veya İmza Sirküsü) ve İşyeri Açma Ruhsatı gibi temel ticari belgelerin PDF formatında yüklenmesi yeterlidir.'],
                    ['q' => 'Teknik destek sağlıyor musunuz?', 'a' => 'Evet, her bir işletme ortağımıza özel bir portföy yöneticisi atanır. Ayrıca 7/24 canlı destek hattımız üzerinden her türlü teknik ve operasyonel sorunuza anında yanıt alabilirsiniz.']
                ] as $index => $faq)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-all duration-300" 
                     :class="active === {{ $index }} ? 'ring-2 ring-primary ring-offset-2' : 'hover:border-slate-300 hover:shadow-md'">
                    <button @click="active === {{ $index }} ? active = null : active = {{ $index }}" 
                            class="flex justify-between items-center w-full p-6 text-left transition-colors"
                            :class="active === {{ $index }} ? 'bg-indigo-50/50' : 'hover:bg-slate-50'">
                        <span class="font-bold text-slate-800 text-lg">{{ $faq['q'] }}</span>
                        <div class="ml-4 flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center transition-transform duration-300"
                             :class="active === {{ $index }} ? 'rotate-180 bg-primary text-white' : 'text-slate-400'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div x-show="active === {{ $index }}" 
                         x-collapse 
                         class="border-t border-slate-100 bg-white">
                        <div class="p-6 text-slate-600 leading-relaxed">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
