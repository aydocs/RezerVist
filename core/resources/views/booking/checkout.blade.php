@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12" x-data="checkoutFlow()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Steps Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 -z-10"></div>
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-primary -z-10 transition-all duration-500" :style="'width: ' + ((step - 1) * 50) + '%'"></div>
                
                <!-- Step 1 -->
                <div class="flex flex-col items-center bg-gray-50 px-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white transition-colors duration-300" 
                         :class="step >= 1 ? 'bg-primary' : 'bg-gray-300'">1</div>
                    <span class="text-xs font-semibold mt-2 text-gray-600">Seçim</span>
                </div>
                
                <!-- Step 2 -->
                <div class="flex flex-col items-center bg-gray-50 px-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white transition-colors duration-300" 
                         :class="step >= 2 ? 'bg-primary' : 'bg-gray-300'">2</div>
                    <span class="text-xs font-semibold mt-2 text-gray-600">Bilgiler</span>
                </div>

                <!-- Step 3 -->
                <div class="flex flex-col items-center bg-gray-50 px-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white transition-colors duration-300" 
                         :class="step >= 3 ? 'bg-primary' : 'bg-gray-300'">3</div>
                    <span class="text-xs font-semibold mt-2 text-gray-600">Ödeme</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Step 1: Selection -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Rezervasyon Detayları</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tarih</label>
                            <input type="date" name="date" x-model="form.date" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring-primary">
                        </div>
                        </div>

                        <!-- Location Selection -->
                        @if($locations->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Şube Seçimi</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div @click="form.location_id = null" 
                                     :class="!form.location_id ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-gray-200'"
                                     class="p-4 border rounded-xl cursor-pointer transition-all hover:border-primary/50">
                                    <p class="font-bold text-gray-900 text-sm">Merkez / Ana Şube</p>
                                    <p class="text-[10px] text-gray-500 font-bold uppercase truncate">{{ $business->address }}</p>
                                </div>
                                <template x-for="loc in {{ $locations->toJson() }}" :key="loc.id">
                                    <div @click="form.location_id = loc.id" 
                                         :class="form.location_id === loc.id ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-gray-200'"
                                         class="p-4 border rounded-xl cursor-pointer transition-all hover:border-primary/50">
                                        <p class="font-bold text-gray-900 text-sm" x-text="loc.name"></p>
                                        <p class="text-[10px] text-gray-500 font-bold uppercase truncate" x-text="loc.city + ' / ' + loc.district"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button @click="step = 2" class="bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-primary/90 transition shadow-lg shadow-primary/30">Devam Et</button>
                    </div>
                </div>

                <!-- Step 2: Guest Details -->
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" style="display: none;">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Misafir Bilgileri</h2>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad</label>
                                <input type="text" x-model="form.name" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring-primary" placeholder="Adınız Soyadınız">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Telefon Numarası</label>
                                <input type="tel" x-model="form.phone" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring-primary" placeholder="05XX XXX XX XX">
                            </div>
                        </div>

                        <!-- Staff Selection -->
                        @if($staff->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Personel Seçimi (Opsiyonel)</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <template x-for="member in {{ $staff->toJson() }}" :key="member.id">
                                    <div @click="form.staff_id = member.id" 
                                         :class="form.staff_id === member.id ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-gray-200'"
                                         class="p-3 border rounded-xl flex flex-col items-center cursor-pointer transition-all hover:border-primary/50 text-center">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-primary font-bold text-xs mb-2 overflow-hidden">
                                            <template x-if="member.photo_path">
                                                <img :src="'/storage/' + member.photo_path" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!member.photo_path">
                                                <span x-text="member.name.charAt(0)"></span>
                                            </template>
                                        </div>
                                        <p class="text-[10px] font-bold text-gray-900 leading-tight" x-text="member.name"></p>
                                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-tighter mt-0.5" x-text="member.position"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Özel İstekler (Opsiyonel)</label>
                            <textarea x-model="form.note" rows="3" class="w-full rounded-xl border-gray-300 focus:border-primary focus:ring-primary" placeholder="Alerji, masa tercihi vb."></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button @click="step = 1" class="text-gray-600 font-medium hover:text-gray-900 transition-colors">Geri Dön</button>
                        <button @click="step = 3" class="bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-primary/90 transition shadow-lg shadow-primary/30 active:scale-95">Ödemeye Geç</button>
                    </div>
                </div>

                <!-- Step 3: Payment Selection -->
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" style="display: none;">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Ödeme ve Onay</h2>
                    
                    <!-- Loyalty Points & Coupon Input -->
                    <div class="mb-8 p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
                        <!-- Loyalty Points -->
                        @if($availablePoints > 0)
                        <div class="p-4 bg-primary/5 rounded-xl border border-primary/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary text-white rounded-lg flex items-center justify-center">
                                    <i class="fas fa-gift"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-primary uppercase tracking-widest">SADAKAT PROGRAMI</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $availablePoints }} Puanınız var (₺{{ number_format($pointsValue, 2, ',', '.') }})</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="use_points" x-model="use_points" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                <span class="ml-3 text-xs font-bold text-gray-500 uppercase">Kullan</span>
                            </label>
                        </div>
                        @endif

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">İNDİRİM KUPONU</label>
                            <div class="flex gap-3">
                                <input type="text" x-model="coupon_code" :disabled="coupon_valid" class="flex-1 rounded-xl border-gray-200 focus:border-primary focus:ring-primary font-bold uppercase placeholder:text-slate-300 text-sm" placeholder="KUPON KODUNU GİRİN">
                                <button type="button" @click="applyCoupon()" :disabled="coupon_loading || coupon_valid" class="px-6 py-2 bg-slate-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-800 transition-all disabled:opacity-50">
                                    <span x-show="!coupon_loading">Uygula</span>
                                    <span x-show="coupon_loading" class="flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                                </button>
                            </div>
                            <template x-if="coupon_valid">
                                <div class="mt-3 flex items-center justify-between bg-emerald-50 p-3 rounded-xl border border-emerald-100">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        <p class="text-xs font-bold text-emerald-800">Kupon Uygulandı: <span x-text="coupon_code"></span></p>
                                    </div>
                                    <button type="button" @click="removeCoupon()" class="text-[10px] font-black text-emerald-600 uppercase underline">Kaldır</button>
                                </div>
                            </template>
                            <template x-if="coupon_error">
                                <p class="mt-2 text-xs text-rose-500 font-bold ml-1" x-text="coupon_error"></p>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <!-- Wallet Option -->
                        <div @click="form.payment_method = 'wallet'" 
                             :class="form.payment_method === 'wallet' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-gray-200 hover:border-gray-300'"
                             class="relative p-5 rounded-2xl border transition-all cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Cüzdan ile Öde</p>
                                    <p class="text-xs text-gray-500">Mevcut Bakiye: <span class="text-primary font-bold">₺{{ number_format(Auth::user()->balance ?? 0, 2, ',', '.') }}</span></p>
                                </div>
                            </div>
                            <div x-show="form.payment_method === 'wallet'" class="absolute top-4 right-4 text-primary">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>

                        <!-- Card Option -->
                        <div @click="form.payment_method = 'card'" 
                             :class="form.payment_method === 'card' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-gray-200 hover:border-gray-300'"
                             class="relative p-5 rounded-2xl border transition-all cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Kredi Kartı</p>
                                    <p class="text-xs text-gray-500">Visa, Mastercard, Troy</p>
                                </div>
                            </div>
                            <div x-show="form.payment_method === 'card'" class="absolute top-4 right-4 text-primary">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div x-show="form.payment_method === 'wallet'" x-collapse>
                        <template x-if="{{ (Auth::user()->balance ?? 0) }} < calculateFinalTotal()">
                            <div class="bg-amber-50 rounded-xl p-4 border border-amber-200 mb-6 flex gap-3">
                                <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <div>
                                    <p class="text-sm text-amber-800 font-bold">Yetersiz Bakiye</p>
                                    <p class="text-xs text-amber-700 mt-0.5">Cüzdan bakiyeniz indirimli tutar için bile yeterli değil.</p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <form id="bookingForm" method="POST" action="/book/{{ $business->id }}">
                        @csrf
                        <input type="hidden" name="date" :value="form.date">
                        <input type="hidden" name="time" :value="form.time">
                        <input type="hidden" name="guests" :value="form.guests">
                        <input type="hidden" name="name" :value="form.name">
                        <input type="hidden" name="phone" :value="form.phone">
                        <input type="hidden" name="note" :value="form.note">
                        <input type="hidden" name="payment_method" :value="form.payment_method">
                        <input type="hidden" name="staff_id" :value="form.staff_id">
                        <input type="hidden" name="location_id" :value="form.location_id">
                        <input type="hidden" name="coupon_code" :value="coupon_code">

                        <div class="mt-8 flex justify-between items-center">
                            <button type="button" @click="step = 2" class="text-gray-600 font-medium hover:text-gray-900 transition-colors">Geri Dön</button>
                            <button type="submit" 
                                    :disabled="(form.payment_method === 'wallet' && {{ (Auth::user()->balance ?? 0) }} < calculateFinalTotal()) || is_submitting"
                                    :class="(form.payment_method === 'wallet' && {{ (Auth::user()->balance ?? 0) }} < calculateFinalTotal()) ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.02] active:scale-95 shadow-lg shadow-green-600/30'"
                                    @click="is_submitting = true"
                                    class="bg-green-600 text-white px-10 py-4 rounded-xl font-bold transition-all flex items-center gap-2">
                                <span x-show="!is_submitting" class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Rezervasyonu Onayla (₺<span x-text="formatMoney(calculateFinalTotal())"></span>)
                                </span>
                                <span x-show="is_submitting" class="flex items-center gap-2"><svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> İşleniyor...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-24">
                    <div class="flex items-center gap-4 mb-6">
                        @if($business->images->first())
                            <img src="{{ asset('storage/' . $business->images->first()->thumbnail_path) }}" class="w-16 h-16 rounded-2xl object-cover shadow-sm">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black">R</div>
                        @endif
                        <div>
                            <h3 class="font-black text-slate-900 leading-tight">{{ $business->name }}</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $business->businessCategory->name ?? '' }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 py-6 border-t border-slate-50">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400 font-bold uppercase tracking-widest">Tarih / Saat</span>
                            <span class="font-black text-slate-900"><span x-text="formatDate(form.date)"></span> @ <span x-text="form.time"></span></span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400 font-bold uppercase tracking-widest">Misafir</span>
                            <span class="font-black text-slate-900" x-text="form.guests + ' Kişi'"></span>
                        </div>
                        <template x-if="form.staff_id">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-400 font-bold uppercase tracking-widest">Personel</span>
                                <span class="font-black text-primary uppercase" x-text="getStaffName(form.staff_id)"></span>
                            </div>
                        </template>
                    </div>

                    <div class="mt-6 pt-6 border-t-2 border-dashed border-slate-100 space-y-3">
                        <div class="flex justify-between items-center text-sm font-bold text-slate-600">
                            <span>Ara Toplam</span>
                            <span>₺{{ number_format($price, 2, ',', '.') }}</span>
                        </div>
                        <template x-if="discount_amount > 0">
                            <div class="flex justify-between items-center text-sm font-bold text-emerald-600">
                                <span>Kupon İndirimi</span>
                                <span>-₺<span x-text="formatMoney(discount_amount)"></span></span>
                            </div>
                        </template>
                        <template x-if="use_points">
                            <div class="flex justify-between items-center text-sm font-bold text-primary">
                                <span>Sadakat İndirimi</span>
                                <span>-₺<span x-text="formatMoney(calculateLoyaltyDiscount())"></span></span>
                            </div>
                        </template>
                        <div class="flex justify-between items-end pt-2">
                            <span class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Ödenecek</span>
                            <span class="text-3xl font-black text-primary tracking-tighter">₺<span x-text="formatMoney(calculateFinalTotal())"></span></span>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <div class="p-4 bg-primary/5 rounded-2xl border border-primary/10 flex items-center gap-3">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <p class="text-[10px] font-bold text-primary tracking-tight leading-snug">Ödemeniz 256-bit SSL ve Iyzico güvencesiyle korunmaktadır.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function checkoutFlow() {
        return {
            step: 1,
            base_price: {{ $price }},
            discount_amount: 0,
            coupon_code: '',
            coupon_valid: false,
            coupon_loading: false,
            coupon_error: '',
            is_submitting: false,
            use_points: false,
            points_value: {{ $pointsValue }},
            form: {
                date: '{{ $date }}',
                time: '{{ $time }}',
                guests: {{ $guests }},
                name: '{{ Auth::user()->name ?? "" }}',
                phone: '{{ Auth::user()->phone ?? "" }}',
                note: '',
                payment_method: 'card',
                staff_id: null,
                location_id: null
            },
            formatDate(dateString) {
                if(!dateString) return '-';
                const date = new Date(dateString);
                return date.toLocaleDateString('tr-TR', { day: 'numeric', month: 'long', year: 'numeric' });
            },
            getStaffName(id) {
                const members = {{ $staff->toJson() }};
                const member = members.find(m => m.id === id);
                return member ? member.name : '';
            },
            async applyCoupon() {
                if(!this.coupon_code) return;
                this.coupon_loading = true;
                this.coupon_error = '';
                
                try {
                    const response = await fetch('{{ route("coupons.validate") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            code: this.coupon_code,
                            amount: this.base_price
                        })
                    });
                    
                    const data = await response.json();
                    
                    if(response.ok && data.valid) {
                        this.discount_amount = data.discount_amount;
                        this.coupon_valid = true;
                        this.coupon_error = '';
                    } else {
                        this.coupon_error = data.message || 'Kupon uygulanamadı.';
                    }
                } catch(e) {
                    this.coupon_error = 'Bir hata oluştu.';
                } finally {
                    this.coupon_loading = false;
                }
            },
            removeCoupon() {
                this.coupon_code = '';
                this.coupon_valid = false;
                this.discount_amount = 0;
            },
            calculateLoyaltyDiscount() {
                if (!this.use_points) return 0;
                let remaining = this.base_price - this.discount_amount;
                return Math.min(remaining, this.points_value);
            },
            calculateFinalTotal() {
                return Math.max(0, this.base_price - this.discount_amount - this.calculateLoyaltyDiscount());
            },
            formatMoney(amount) {
                return new Intl.NumberFormat('tr-TR', { minimumFractionDigits: 2 }).format(amount);
            }
        }
    }
</script>
@endsection
