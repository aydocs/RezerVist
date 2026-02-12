@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">İşletme Profili</h1>
                <p class="text-gray-500 mt-1">İşletme bilgilerinizi güncelleyin ve yönetin.</p>
            </div>
            <div class="flex items-center gap-3">
                 <a href="{{ route('vendor.dashboard') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Panele Dön
                 </a>
                 <a href="/business/{{ $business->id }}" target="_blank" class="px-5 py-2.5 bg-primary/10 text-primary border border-primary/20 font-bold rounded-xl hover:bg-primary/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Sayfayı Görüntüle
                 </a>
            </div>
        </div>

        <!-- Occupancy Rate Dashboard -->
        <div class="mb-8 bg-gradient-to-br from-primary to-purple-700 rounded-3xl shadow-xl p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-widest opacity-80">Anlık Doluluk Oranı</h3>
                    <p class="text-xs opacity-60 mt-1">POS Sistemi Entegrasyonu</p>
                </div>
                @if($business->last_occupancy_update)
                    <div class="text-xs opacity-75">
                        Son güncelleme: {{ $business->last_occupancy_update->diffForHumans() }}
                    </div>
                @endif
            </div>
            
            <div class="flex items-end gap-6">
                <div class="flex-1">
                    <div class="text-7xl font-black mb-2">{{ $business->occupancy_rate }}%</div>
                    <div class="w-full bg-white/20 rounded-full h-4 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 {{ $business->occupancy_rate >= 85 ? 'bg-rose-400' : ($business->occupancy_rate >= 60 ? 'bg-amber-400' : 'bg-emerald-400') }}" 
                             style="width: {{ $business->occupancy_rate }}%"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-4">
                        <div class="text-2xl font-black">{{ $business->resources->count() }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider opacity-75">Toplam Masa</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-4">
                        <div class="text-2xl font-black">{{ $business->reservations()->whereDate('start_time', today())->count() }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider opacity-75">Bugün Rezervasyon</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-4">
                        <div class="text-2xl font-black">{{ $business->reservations()->where('status', 'checked_in')->sum('guest_count') }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider opacity-75">Şu An Misafir</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Left Column: Form -->
            <div class="space-y-8">
                <form id="businessUpdateForm" action="{{ route('vendor.business.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Basic Info Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <h2 class="font-bold text-gray-900">Temel Bilgiler</h2>
                        </div>
                        <div class="p-5 sm:p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">İşletme Adı</label>
                                    <input type="text" name="name" value="{{ old('name', $business->name) }}" class="w-full px-4 py-2 sm:py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition outline-none font-medium" placeholder="Örn: Paşa Döner">
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Telefon Numarası</label>
                                    <input type="text" name="phone" value="{{ old('phone', $business->phone) }}" class="w-full px-4 py-2 sm:py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition outline-none font-medium" placeholder="0555 555 55 55">
                                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                                    <select name="category" class="w-full px-4 py-2 sm:py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition outline-none font-medium bg-white">
                                        @foreach(['Restoran', 'Kafe', 'Bar & Pub', 'Kuaför', 'Güzellik Merkezi', 'Spor Salonu', 'Diğer'] as $cat)
                                            <option value="{{ $cat }}" {{ old('category', $business->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Min. Tutar (₺)</label>
                                    <input type="number" name="min_reservation_amount" value="{{ old('min_reservation_amount', $business->min_reservation_amount) }}" class="w-full px-4 py-2 sm:py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition outline-none font-medium" placeholder="0">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hakkında & Açıklama</label>
                                <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition outline-none text-sm text-gray-600" placeholder="İşletmeniz hakkında müşterilerinize bilgi verin...">{{ old('description', $business->description) }}</textarea>
                                <p class="text-xs text-gray-400 mt-2 text-right">En az 20 karakter</p>
                                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Öne Çıkan Özellikler (Etiketler)</label>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @php
                                        $currentFeatures = is_string($business->features) ? json_decode($business->features, true) : ($business->features ?? []);
                                        $availableFeatures = ['Wifi', 'Otopark', 'Teras', 'Canlı Müzik', 'Deniz Manzarası', 'Kredi Kartı', 'Vegan Seçenekler', 'Evcil Hayvan Dostu'];
                                    @endphp
                                    @foreach($availableFeatures as $feature)
                                        <label class="inline-flex items-center cursor-pointer select-none">
                                            <input type="checkbox" name="tags[]" value="{{ $feature }}" class="peer sr-only" {{ in_array($feature, $currentFeatures ?? []) ? 'checked' : '' }}>
                                            <span class="px-3 py-1.5 rounded-lg border border-gray-200 bg-white text-gray-600 text-sm peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary transition hover:bg-gray-50">{{ $feature }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-400">İşletmenizi en iyi tanımlayan özellikleri seçin.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Location Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <h2 class="font-bold text-gray-900">Konum Bilgileri</h2>
                        </div>
                        <div class="p-5 sm:p-8 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Açık Adres</label>
                                <textarea name="address" rows="2" class="w-full px-4 py-2 sm:py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition outline-none text-sm text-gray-600" placeholder="Mahalle, Cadde, Sokak No... ">{{ old('address', $business->address) }}</textarea>
                                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Map Picker -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harita Konumu</label>
                                <div id="map" class="w-full h-64 rounded-xl border border-gray-200 z-0"></div>
                                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $business->latitude ?? 39.9334) }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $business->longitude ?? 32.8597) }}">
                                <p class="text-xs text-gray-400 mt-2">Harita üzerinde işletmenizin tam konumunu işaretleyin.</p>
                            </div>
                        </div>
                    </div>




@if(false)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-2 mt-8 bg-white/5 p-8 rounded-[2rem] border border-white/5">
                                <div class="md:col-span-2">
                                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-4">SİSTEM MASTER PIN (ADMIN)</label>
                                    <div class="relative group">
                                        <div class="absolute left-6 top-1/2 -translate-y-1/2 text-primary">
                                            <i class="fa-solid fa-shield-halved text-xl"></i>
                                        </div>
                                        <input type="text" name="master_pin" maxlength="8" placeholder="8 Haneli PIN (Örn: 00000000)" 
                                               value="{{ old('master_pin', $business->master_pin) }}"
                                               class="w-full pl-16 pr-6 py-5 bg-black/40 border-2 border-white/5 rounded-2xl focus:border-primary outline-none text-white font-mono text-xl tracking-[0.5em] transition-all placeholder:text-gray-700 placeholder:tracking-normal placeholder:text-sm">
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-4 leading-relaxed font-bold italic">
                                        * Bu PIN kodu POS uygulamasında iptal, iade ve rapor ekranlarına erişmek için gereklidir. Varsayılan: <span class="text-white">00000000</span>
                                    </p>
                                </div>
                                <div class="flex items-center justify-center">
                                    <div class="text-center p-6 bg-primary/5 rounded-3xl border border-primary/10">
                                        <div class="w-12 h-12 bg-primary/20 text-primary rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fa-solid fa-lock text-xl"></i>
                                        </div>
                                        <p class="text-[10px] font-black text-white uppercase tracking-widest">GÜVENLİ ERİŞİM</p>
                                        <p class="text-gray-500 text-[9px] mt-1 italic">8 Karakterli Sayısal Kod</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
@endif

                    <!-- Reservation Time Slots Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8 relative z-20">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                            <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                                <i class="fa-solid fa-calendar-clock text-lg"></i>
                            </div>
                            <h2 class="font-bold text-gray-900">Rezervasyon Saat Ayarları</h2>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <!-- Start Time Custom Dropdown -->
                                <div class="space-y-2" x-data="{ 
                                    open: false, 
                                    value: '{{ old('reservation_start_time', $business->reservation_time_slots[0]['start'] ?? '10:00') }}',
                                    times: Array.from({length: 48}, (_, i) => {
                                        const h = Math.floor(i/2).toString().padStart(2, '0');
                                        const m = (i%2 === 0 ? '00' : '30');
                                        return h + ':' + m;
                                    })
                                }">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest px-1">BAŞLANGIÇ SAATİ</label>
                                    <div class="relative">
                                        <button type="button" @click="open = !open" 
                                                class="w-full flex items-center gap-4 pl-5 pr-6 py-4 bg-gray-50 border-2 transition-all rounded-[1.5rem] font-bold text-gray-900 group"
                                                :class="open ? 'border-primary bg-white shadow-lg shadow-primary/5' : 'border-transparent hover:bg-gray-100'">
                                            <i class="fa-regular fa-clock text-gray-400 group-hover:text-primary transition-colors" :class="open && 'text-primary'"></i>
                                            <span x-text="value"></span>
                                            <i class="fa-solid fa-chevron-down ml-auto text-xs text-gray-400 transition-transform" :class="open && 'rotate-180'"></i>
                                        </button>
                                        
                                        <div x-show="open" @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 translate-y-2"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             class="absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-[1.5rem] shadow-2xl max-h-60 overflow-y-auto custom-scrollbar p-2">
                                            <template x-for="time in times" :key="time">
                                                <button type="button" @click="value = time; open = false" 
                                                        class="w-full text-left px-4 py-3 rounded-xl hover:bg-primary/5 hover:text-primary transition-all font-bold text-sm"
                                                        :class="value === time ? 'bg-primary/10 text-primary' : 'text-gray-600'">
                                                    <span x-text="time"></span>
                                                </button>
                                            </template>
                                        </div>
                                        <input type="hidden" name="reservation_start_time" :value="value">
                                    </div>
                                </div>

                                <!-- End Time Custom Dropdown -->
                                <div class="space-y-2" x-data="{ 
                                    open: false, 
                                    value: '{{ old('reservation_end_time', $business->reservation_time_slots[0]['end'] ?? '23:00') }}',
                                    times: Array.from({length: 48}, (_, i) => {
                                        const h = Math.floor(i/2).toString().padStart(2, '0');
                                        const m = (i%2 === 0 ? '00' : '30');
                                        return h + ':' + m;
                                    })
                                }">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest px-1">BİTİŞ SAATİ</label>
                                    <div class="relative">
                                        <button type="button" @click="open = !open" 
                                                class="w-full flex items-center gap-4 pl-5 pr-6 py-4 bg-gray-50 border-2 transition-all rounded-[1.5rem] font-bold text-gray-900 group"
                                                :class="open ? 'border-primary bg-white shadow-lg shadow-primary/5' : 'border-transparent hover:bg-gray-100'">
                                            <i class="fa-regular fa-clock text-gray-400 group-hover:text-primary transition-colors" :class="open && 'text-primary'"></i>
                                            <span x-text="value"></span>
                                            <i class="fa-solid fa-chevron-down ml-auto text-xs text-gray-400 transition-transform" :class="open && 'rotate-180'"></i>
                                        </button>
                                        
                                        <div x-show="open" @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 translate-y-2"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             class="absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-[1.5rem] shadow-2xl max-h-60 overflow-y-auto custom-scrollbar p-2">
                                            <template x-for="time in times" :key="time">
                                                <button type="button" @click="value = time; open = false" 
                                                        class="w-full text-left px-4 py-3 rounded-xl hover:bg-primary/5 hover:text-primary transition-all font-bold text-sm"
                                                        :class="value === time ? 'bg-primary/10 text-primary' : 'text-gray-600'">
                                                    <span x-text="time"></span>
                                                </button>
                                            </template>
                                        </div>
                                        <input type="hidden" name="reservation_end_time" :value="value">
                                    </div>
                                </div>

                                <!-- Slot Duration Custom Dropdown -->
                                <div class="space-y-2" x-data="{ 
                                    open: false, 
                                    value: '{{ $business->reservation_time_slots[0]['slot_duration'] ?? 60 }}',
                                    options: [
                                        { val: '30', label: '30 Dakika' },
                                        { val: '60', label: '1 Saat' },
                                        { val: '90', label: '1.5 Saat' },
                                        { val: '120', label: '2 Saat' }
                                    ],
                                    get label() { return this.options.find(o => o.val == this.value)?.label || 'Seçiniz' }
                                }">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest px-1">MASA PERİYODU</label>
                                    <div class="relative">
                                        <button type="button" @click="open = !open" 
                                                class="w-full flex items-center gap-4 pl-5 pr-6 py-4 bg-gray-50 border-2 transition-all rounded-[1.5rem] font-bold text-gray-900 group"
                                                :class="open ? 'border-black bg-white shadow-lg shadow-black/5' : 'border-transparent hover:bg-gray-100'">
                                            <i class="fa-solid fa-timeline text-gray-400 group-hover:text-primary transition-colors" :class="open && 'text-primary'"></i>
                                            <span x-text="label"></span>
                                            <i class="fa-solid fa-chevron-down ml-auto text-xs text-gray-400 transition-transform" :class="open && 'rotate-180'"></i>
                                        </button>
                                        
                                        <div x-show="open" @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 translate-y-2"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             class="absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-[1.5rem] shadow-2xl p-2"
                                             style="scrollbar-width: thin; scrollbar-color: rgba(0, 0, 0, 0.2) transparent;">
                                            <template x-for="opt in options" :key="opt.val">
                                                <button type="button" @click="value = opt.val; open = false" 
                                                        class="w-full text-left px-5 py-3 rounded-xl hover:bg-black/5 hover:text-black transition-all font-bold text-[13px]"
                                                        :class="value === opt.val ? 'bg-black/10 text-black' : 'text-gray-600'">
                                                    <span x-text="opt.label"></span>
                                                </button>
                                            </template>
                                        </div>
                                        <input type="hidden" name="reservation_slot_duration" :value="value">
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 italic">Müşteriler bu saat aralıklarında rezervasyon yapabilecekler.</p>
                        </div>
                    </div>

                    <!-- POS Readiness & Setup Center -->
                    <div class="bg-gray-900 rounded-[2rem] shadow-2xl shadow-indigo-500/10 overflow-hidden mb-8 border border-white/5 relative z-10" x-data="{ seeding: false }">
                        <div class="p-8 border-b border-white/5 bg-gradient-to-r from-gray-900 via-indigo-950 to-gray-900 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                                    <i class="fa-solid fa-rocket text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="font-black text-xl text-white tracking-tight">POS Hazırlık Merkezi</h2>
                                    <p class="text-indigo-400/60 text-xs font-bold uppercase tracking-widest">Sistemi Kullanmaya Başlamadan Önce</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" 
                                        @click="if(confirm('Tüm menü, masa ve personel verileriniz silinecektir. Onaylıyor musunuz?')) {
                                                seeding = true; 
                                                fetch('{{ route('vendor.business.clear-demo-data') }}', {
                                                    method: 'POST',
                                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                                })
                                                .then(r => r.json())
                                                .then(d => {
                                                    if(d.success) {
                                                        Swal.fire({ icon: 'warning', title: 'Sıfırlandı!', text: d.message, confirmButtonColor: '#ff4444' })
                                                        .then(() => window.location.reload());
                                                    }
                                                })
                                                .finally(() => seeding = false)
                                            }"
                                        class="px-5 py-3 bg-red-600/10 hover:bg-red-600 text-red-500 hover:text-white rounded-xl font-bold text-sm transition-all flex items-center gap-2 border border-red-500/20 disabled:opacity-50"
                                        :disabled="seeding">
                                    <i class="fa-solid fa-trash-can"></i>
                                    Sıfırla
                                </button>
                                <button type="button" 
                                        @click="seeding = true; 
                                                fetch('{{ route('vendor.business.seed-demo-data') }}', {
                                                    method: 'POST',
                                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                                })
                                                .then(r => r.json())
                                                .then(d => {
                                                    if(d.success) {
                                                        Swal.fire({ icon: 'success', title: 'Harika!', text: d.message, confirmButtonColor: '#6366f1' })
                                                        .then(() => window.location.reload());
                                                    }
                                                })
                                                .finally(() => seeding = false)"
                                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-lg shadow-indigo-500/20 disabled:opacity-50"
                                        :disabled="seeding">
                                    <i class="fa-solid fa-wand-magic-sparkles" :class="seeding && 'animate-spin'"></i>
                                    <span x-text="seeding ? 'YÜKLENİYOR...' : 'DEMO VERİLERİ YÜKLE'"></span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Step 1: Menu -->
                                <div class="bg-white/5 p-6 rounded-3xl border border-white/5 hover:border-white/10 transition-colors group">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 {{ $business->menus()->count() > 0 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-white/5 text-gray-500' }} rounded-xl flex items-center justify-center border border-white/5">
                                            <i class="fa-solid {{ $business->menus()->count() > 0 ? 'fa-check' : 'fa-list-ul' }}"></i>
                                        </div>
                                        <span class="font-black text-xs text-white/50 uppercase tracking-widest">Menü & Ürünler</span>
                                    </div>
                                    <h3 class="text-white font-bold mb-2">Kategori ve Ürünler</h3>
                                    <p class="text-white/40 text-[11px] leading-relaxed mb-4">Adisyon açabilmek için en az bir kategori ve ürün eklemelisiniz.</p>
                                    <a href="{{ route('vendor.menus.index') }}" class="inline-flex items-center text-xs font-black text-indigo-400 hover:text-indigo-300 gap-2 uppercase tracking-widest">
                                        YÖNET <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>

                                <!-- Step 2: Tables -->
                                <div class="bg-white/5 p-6 rounded-3xl border border-white/5 hover:border-white/10 transition-colors group">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 {{ $business->resources()->count() > 0 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-white/5 text-gray-500' }} rounded-xl flex items-center justify-center border border-white/5">
                                            <i class="fa-solid {{ $business->resources()->count() > 0 ? 'fa-check' : 'fa-table-cells-large' }}"></i>
                                        </div>
                                        <span class="font-black text-xs text-white/50 uppercase tracking-widest">Masa & Kat</span>
                                    </div>
                                    <h3 class="text-white font-bold mb-2">Masa & Kat Planı</h3>
                                    <p class="text-white/40 text-[11px] leading-relaxed mb-4">Salon veya Bahçe gibi alanlar oluşturup masalarınızı yerleştirin.</p>
                                    <a href="{{ route('vendor.resources.index') }}" class="inline-flex items-center text-xs font-black text-indigo-400 hover:text-indigo-300 gap-2 uppercase tracking-widest">
                                        YÖNET <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>

                                <!-- Step 3: Staff -->
                                <div class="bg-white/5 p-6 rounded-3xl border border-white/5 hover:border-white/10 transition-colors group">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 {{ $business->staff()->count() > 0 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-white/5 text-gray-500' }} rounded-xl flex items-center justify-center border border-white/5">
                                            <i class="fa-solid {{ $business->staff()->count() > 0 ? 'fa-check' : 'fa-users' }}"></i>
                                        </div>
                                        <span class="font-black text-xs text-white/50 uppercase tracking-widest">Personel & PIN</span>
                                    </div>
                                    <h3 class="text-white font-bold mb-2">Personel PIN Kodları</h3>
                                    <p class="text-white/40 text-[11px] leading-relaxed mb-4">Garsonların POS app'ine giriş yapabilmesi için 4 haneli PIN kodları.</p>
                                    <a href="{{ route('vendor.staff.index') }}" class="inline-flex items-center text-xs font-black text-indigo-400 hover:text-indigo-300 gap-2 uppercase tracking-widest">
                                        YÖNET <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>

                            </div>

                            <div class="mt-8 p-4 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl flex items-center gap-4">
                                <div class="p-2 bg-indigo-500/20 text-indigo-400 rounded-lg">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <p class="text-indigo-200/70 text-xs font-medium">
                                    <strong class="text-white">İpucu:</strong> Eğer bu ayarları tek tek yapmak istemiyorsanız, yukarıdaki <span class="text-indigo-400">"Demo Verileri Yükle"</span> butonunu kullanarak saniyeler içinde hazır hale gelebilirsiniz.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Images Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <h2 class="font-bold text-gray-900">İşletme Fotoğrafları</h2>
                            </div>
                        </div>
                        <div class="p-8">
                            <!-- Existing Images Grid -->
                            @if($business->images->count() > 0)
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                    @foreach($business->images as $image)
                                        <div class="group relative aspect-square rounded-xl overflow-hidden border border-gray-200">
                                            <img src="{{ Storage::url($image->image_path) }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                                <button type="button" onclick="document.getElementById('delete-image-{{ $image->id }}').submit()" class="p-2 bg-white rounded-full text-red-500 hover:text-red-700 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 mb-6">
                                    Henüz fotoğraf yüklemediniz.
                                </div>
                            @endif


                            <!-- Upload Area using a separate form to avoid nesting -->
                        </div>
                    </div>
                    
                    <!-- Separate Upload Form Area (Visually inside, logic outside main form if needed, or we use JS) -->
                    <!-- Since we want to upload images separately from updating details, we can use a separate small form or handle it differently. 
                         For simplicity and UX, let's keep the main form for details and add a dedicated upload section that submits immediately 
                         OR we can use the main form to upload.
                         
                         Let's use a separate form for "Instant Upload" UX to avoid resetting the main form if it has unsaved changes.
                    -->
                    <div id="drop-zone" class="bg-gray-50 rounded-xl p-8 border-2 border-dashed border-gray-300 hover:border-primary hover:bg-purple-50 transition cursor-pointer text-center relative" onclick="document.getElementById('image-upload').click()">
                        <div class="pointer-events-none space-y-2">
                            <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="font-medium text-gray-900">Fotoğraf Yüklemek İçin Tıklayın veya Sürükleyin</p>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP (Max 2MB)</p>
                        </div>
                        <!-- Drag Overlay -->
                        <div id="drag-overlay" class="absolute inset-0 bg-primary/10 border-2 border-primary rounded-xl hidden items-center justify-center">
                            <p class="font-bold text-primary">Dosyayı Buraya Bırakın</p>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="flex items-center justify-end gap-3 pt-4">

                        <button type="button" id="saveButton" class="px-8 py-4 bg-primary text-white font-bold rounded-xl hover:bg-purple-700 transition shadow-lg shadow-primary/30 flex items-center gap-2">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                             Değişiklikleri Kaydet
                        </button>
                    </div>
                </form>
            </div>






            </div>
        </div>
    </div>

    <!-- Hidden Delete Forms -->
    @foreach($business->images as $image)
        <form id="delete-image-{{ $image->id }}" action="{{ route('vendor.business.images.delete', $image->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <!-- Image Upload Form (Hidden) -->
    <form id="image-upload-form" action="{{ route('vendor.business.images.store') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="file" name="image" id="image-upload" onchange="document.getElementById('image-upload-form').submit()">
    </form>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Map Initialization (Safe Mode) ---
        try {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const mapContainer = document.getElementById('map');
            
            if (mapContainer && typeof L !== 'undefined') {
                const initialLat = parseFloat(latInput.value) || 39.9334;
                const initialLng = parseFloat(lngInput.value) || 32.8597;

                const map = L.map('map').setView([initialLat, initialLng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                let marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);

                function updateInputs(lat, lng) {
                    latInput.value = lat;
                    lngInput.value = lng;
                }

                marker.on('dragend', function(event) {
                    const position = marker.getLatLng();
                    updateInputs(position.lat, position.lng);
                });

                map.on('click', function(e) {
                    marker.setLatLng(e.latlng);
                    updateInputs(e.latlng.lat, e.latlng.lng);
                });
            } else {
                console.warn('Map container missing or Leaflet not loaded.');
            }
        } catch (e) {
            console.error('Map initialization error:', e);
        }

        // --- 2. Image Drag & Drop Logic ---
        try {
            const dropZone = document.getElementById('drop-zone');
            const dragOverlay = document.getElementById('drag-overlay');
            const fileInput = document.getElementById('image-upload');
            const uploadForm = document.getElementById('image-upload-form');

            if (dropZone && fileInput && uploadForm) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, unhighlight, false);
                });

                function highlight(e) {
                    dropZone.classList.add('border-primary', 'bg-purple-50');
                    if(dragOverlay) {
                         dragOverlay.classList.remove('hidden');
                         dragOverlay.classList.add('flex');
                    }
                }

                function unhighlight(e) {
                    dropZone.classList.remove('border-primary', 'bg-purple-50');
                    if(dragOverlay) {
                        dragOverlay.classList.add('hidden');
                        dragOverlay.classList.remove('flex');
                    }
                }

                dropZone.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;

                    if (files.length > 0) {
                        fileInput.files = files;
                        uploadForm.submit();
                    }
                }
            }
        } catch (e) {
            console.error('Drag and drop error:', e);
        }

        // --- 3. Save Confirmation Logic ---
        const saveButton = document.getElementById('saveButton');
        const updateForm = document.getElementById('businessUpdateForm');

        if (saveButton && updateForm) {
            saveButton.addEventListener('click', function(e) {
                e.preventDefault(); // Stop any default button behavior (doubly sure)
                
                // Check if showConfirm is available
                if (typeof showConfirm === 'function') {
                    showConfirm('Değişiklikleri Kaydet', 'Yaptığınız değişiklikler kaydedilecek. Onaylıyor musunuz?', 'Evet, Kaydet', 'Vazgeç')
                        .then((result) => {
                            if (result.isConfirmed) {
                                if (typeof showToast === 'function') showToast('Kaydediliyor...', 'info');
                                updateForm.submit();
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                if (typeof showToast === 'function') showToast('İşlem iptal edildi.', 'info');
                            }
                        });
                } else {
                    // Fallback if sweetalert is missing
                    if (confirm('Değişiklikleri kaydetmek istiyor musunuz?')) {
                        updateForm.submit();
                    }
                }
            });
        } else {
            console.error('Save button or form not found.');
        }
    });

                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Dispatch event for Alpine.js update
                window.dispatchEvent(new CustomEvent('token-updated', { detail: { token: data.token } }));
                if (typeof showToast === 'function') showToast('Yeni Lisans Anahtarı Üretildi!', 'success');
                
                Swal.fire({
                    title: 'İşlem Başarılı',
                    text: 'Yeni lisan anahtarınız üretildi ve kaydedildi.',
                    icon: 'success',
                    confirmButtonColor: '#6200EE',
                    customClass: { popup: 'rounded-[2rem]' }
                });
            } else {
                Swal.fire('Hata!', data.message || 'Bir hata oluştu.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Hata!', 'Sistem hatası oluştu.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        });
    }

    function confirmResetDevice() {
        if (typeof Swal === 'undefined') {
            if (confirm('Cihaz bağlantısını sıfırlamak istediğinize emin misiniz?')) {
                resetPosDevice();
            }
            return;
        }

        Swal.fire({
            title: 'Cihaz Bağlantısını Sıfırla',
            text: "Bu işlem mevcut cihazın bağlantısını koparacaktır. Yeni bir cihazdan giriş yapabilmeniz için gereklidir. Onaylıyor musunuz?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#F43F5E',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: 'Evet, Sıfırla',
            cancelButtonText: 'İptal',
            customClass: { popup: 'rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                resetPosDevice();
            }
        });
    }

    function resetPosDevice() {
        const btn = document.querySelector('button[onclick="confirmResetDevice()"]');
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

        fetch('{{ route("vendor.business.reset-pos-device") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof showToast === 'function') showToast(data.message, 'success');
                Swal.fire({
                    title: 'Başarılı',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#6200EE',
                    customClass: { popup: 'rounded-[2rem]' }
                });
            } else {
                Swal.fire('Hata!', data.message || 'Sıfırlama sırasında bir hata oluştu.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Hata!', 'Sistem hatası oluştu.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        });
    }
</script>
@endsection
