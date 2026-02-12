<div x-data="cookieConsent()" x-init="init()" x-cloak>
    <!-- Main Banner -->
    <div x-show="!consented && showBanner" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         class="fixed bottom-0 inset-x-0 z-50 bg-gray-900/95 backdrop-blur-md border-t border-gray-700 shadow-2xl p-4 md:p-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1 text-center md:text-left space-y-2">
                <h3 class="text-white font-semibold text-lg">Çerez Tercihleriniz</h3>
                <p class="text-gray-300 text-sm leading-relaxed max-w-3xl">
                    Size daha iyi bir deneyim sunmak, site trafiğini analiz etmek ve kişiselleştirilmiş içerik sunmak için çerezleri kullanıyoruz.
                    <a href="{{ route('pages.cookies') }}" class="text-primary hover:text-primary-400 hover:underline font-medium transition-colors">Çerez Politikamızı</a> inceleyebilirsiniz.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <button @click="openPreferences()" 
                        class="px-5 py-2.5 text-sm font-medium text-gray-300 bg-white/5 hover:bg-white/10 hover:text-white rounded-xl transition-all duration-200 border border-white/10">
                    Çerezleri Yönet
                </button>
                <button @click="acceptAll()" 
                        class="px-8 py-2.5 text-sm font-bold text-white bg-primary hover:bg-primary-600 rounded-xl shadow-lg shadow-primary/25 transition-all duration-200 transform hover:scale-105 active:scale-95">
                    Tümünü Kabul Et
                </button>
            </div>
        </div>
    </div>

    <!-- Preferences Modal -->
    <div x-show="showModal" 
         class="fixed inset-0 z-[60] overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"
             @click="showModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-gray-100">
                
                <!-- Modal Header -->
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Çerez Ayarları</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-4 py-5 sm:p-6 space-y-6 max-h-[60vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-200">
                    <p class="text-sm text-gray-500">
                        Web sitemizin düzgün çalışması için bazı çerezler zorunludur. Diğer çerezleri tercihlerinize göre etkinleştirebilirsiniz.
                    </p>

                    <!-- Strictly Necessary -->
                    <div class="flex items-start justify-between p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="flex-1 pr-4">
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-semibold text-gray-900">Zorunlu Çerezler</h4>
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Gerekli</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Sitenin temel işlevleri için gereklidir. Bu çerezler kapatılamaz.</p>
                        </div>
                        <div class="flex h-6 items-center">
                            <input type="checkbox" disabled checked class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary/25 cursor-not-allowed opacity-50">
                        </div>
                    </div>

                    <!-- Analytics -->
                    <div class="flex items-start justify-between p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                        <div class="flex-1 pr-4">
                            <h4 class="text-sm font-semibold text-gray-900">Analitik Çerezler</h4>
                            <p class="mt-1 text-xs text-gray-500">Site performansını ölçmek ve iyileştirmek için ziyaretleri saymamıza yardımcı olur.</p>
                        </div>
                        <div class="flex h-6 items-center">
                            <div class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                 :class="preferences.analytics ? 'bg-primary' : 'bg-gray-200'"
                                 @click="preferences.analytics = !preferences.analytics">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                      :class="preferences.analytics ? 'translate-x-5' : 'translate-x-0'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Business Recommendations -->
                    <div class="flex items-start justify-between p-4 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                        <div class="flex-1 pr-4">
                            <h4 class="text-sm font-semibold text-gray-900">İşletme Önerileri</h4>
                            <p class="mt-1 text-xs text-gray-500">Ziyaret geçmişinize göre sevdiğiniz tarzda yeni restoran ve mekan önerileri sunmamıza yardımcı olur.</p>
                        </div>
                        <div class="flex h-6 items-center">
                            <div class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                 :class="preferences.marketing ? 'bg-primary' : 'bg-gray-200'"
                                 @click="preferences.marketing = !preferences.marketing">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                      :class="preferences.marketing ? 'translate-x-5' : 'translate-x-0'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100 gap-2">
                    <button type="button" 
                            @click="savePreferences()"
                            class="inline-flex w-full justify-center rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600 sm:w-auto transition-colors">
                        Tercihleri Kaydet
                    </button>
                    <button type="button" 
                            @click="showModal = false"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                        İptal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function cookieConsent() {
        return {
            showBanner: false,
            showModal: false,
            consented: false,
            preferences: {
                necessary: true,
                analytics: false,
                marketing: false
            },
            
            init() {
                const stored = localStorage.getItem('rezervist_cookie_preferences');
                if (stored) {
                    this.consented = true;
                    this.preferences = JSON.parse(stored);
                    this.dispatchUpdate();
                } else {
                    setTimeout(() => {
                        this.showBanner = true;
                    }, 1000);
                }
            },

            openPreferences() {
                this.showModal = true;
            },

            acceptAll() {
                this.preferences = {
                    necessary: true,
                    analytics: true,
                    marketing: true
                };
                this.saveToStorage();
            },

            savePreferences() {
                this.saveToStorage();
            },

            saveToStorage() {
                localStorage.setItem('rezervist_cookie_preferences', JSON.stringify(this.preferences));
                this.consented = true;
                this.showModal = false;
                this.showBanner = false;
                this.dispatchUpdate();
            },

            dispatchUpdate() {
                window.dispatchEvent(new CustomEvent('cookie-consent-updated', { 
                    detail: this.preferences 
                }));
                
                // GTM/GA4 compatibility (DataLayer)
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({
                    'event': 'cookie_consent_update',
                    'cookie_preferences': this.preferences
                });
            }
        }
    }
</script>
