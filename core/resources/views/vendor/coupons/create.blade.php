@extends('layouts.app')



@section('content')
<div class="min-h-screen bg-gray-50/50 pb-20" x-data="couponForm({ 
    code: '{{ old('code', '') }}', 
    type: '{{ old('type', 'percentage') }}', 
    value: '{{ old('value', '') }}', 
    min_amount: '{{ old('min_amount', '') }}',
    expires_at: '{{ old('expires_at', '') }}' 
})">
    <!-- Header -->
    <div class="bg-white border-b border-gray-100 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-4">
                    <a href="{{ route('vendor.coupons.index') }}" class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-black text-gray-900 tracking-tight">Yeni Kupon Oluştur</h1>
                        <p class="text-xs text-gray-500 font-medium">Müşterilerinize özel indirimler tanımlayın</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <form action="{{ route('vendor.coupons.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                
                <!-- LEFT COLUMN: FORM EDITOR -->
                <div class="lg:col-span-7 space-y-8">
                    
                    <!-- Main Settings Card -->
                    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 relative group hover:shadow-lg hover:shadow-gray-100/50 transition-all duration-300">
                        <div class="absolute inset-0 overflow-hidden rounded-[32px] pointer-events-none">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-[100px] -mr-8 -mt-8 opacity-50 group-hover:scale-110 transition-transform"></div>
                        </div>
                        
                        <h2 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm">
                                <i class="fas fa-sliders-h"></i>
                            </span>
                            Temel Bilgiler
                        </h2>

                        <div class="space-y-6 relative">
                            <!-- Code Input -->
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kupon Kodu</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-gray-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                    </div>
                                    <input type="text" name="code" x-model="code" 
                                        class="w-full pl-11 pr-4 py-4 bg-gray-50 border-2 border-gray-100 text-gray-900 rounded-2xl focus:ring-0 focus:border-indigo-500 focus:bg-white font-mono text-lg font-bold uppercase placeholder-gray-300 transition-all" 
                                        placeholder="ÖRNEK: YAZ2024" required>
                                </div>
                                <p class="text-[11px] text-gray-400 font-medium mt-2 pl-1">Benzersiz bir kod girin. Türkçe karakter içermemesi önerilir.</p>
                                @error('code') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <!-- Type & Value Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Type Selection -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">İndirim Tipi</label>
                                    <div class="bg-gray-50 p-1.5 rounded-2xl border border-gray-100 flex shadow-inner">
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" name="type" value="percentage" x-model="type" class="sr-only">
                                            <div class="py-3 rounded-xl text-center text-sm font-bold transition-all relative overflow-hidden" 
                                                :class="type === 'percentage' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5' : 'text-gray-400 hover:text-gray-600'">
                                                % Yüzde
                                            </div>
                                        </label>
                                        <label class="flex-1 cursor-pointer">
                                            <input type="radio" name="type" value="fixed" x-model="type" class="sr-only">
                                            <div class="py-3 rounded-xl text-center text-sm font-bold transition-all" 
                                                :class="type === 'fixed' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5' : 'text-gray-400 hover:text-gray-600'">
                                                ₺ Tutar
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Value Input -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">İndirim Değeri</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <template x-if="type === 'percentage'">
                                                <i class="fas fa-percent text-gray-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                            </template>
                                            <template x-if="type === 'fixed'">
                                                <span class="text-gray-400 font-bold group-focus-within:text-indigo-500 transition-colors">₺</span>
                                            </template>
                                        </div>
                                        <input type="number" step="0.01" name="value" x-model="value" 
                                            class="w-full pl-11 pr-4 py-4 bg-gray-50 border-2 border-gray-100 text-gray-900 rounded-2xl focus:ring-0 focus:border-indigo-500 focus:bg-white font-bold text-lg placeholder-gray-300 transition-all" 
                                            placeholder="10" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Settings Card -->
                    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 relative group hover:shadow-lg hover:shadow-gray-100/50 transition-all duration-300">
                        <div class="absolute inset-0 overflow-hidden rounded-[32px] pointer-events-none">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-bl-[100px] -mr-8 -mt-8 opacity-50 group-hover:scale-110 transition-transform"></div>
                        </div>

                        <h2 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm">
                                <i class="fas fa-cogs"></i>
                            </span>
                            Kısıtlamalar & Kurallar
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">
                            <!-- Min Amount -->
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Min. Sepet Tutarı</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-400 font-bold group-focus-within:text-orange-500 transition-colors">₺</span>
                                    </div>
                                    <input type="number" step="0.01" name="min_amount" x-model="min_amount"
                                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border-2 border-gray-100 text-gray-900 rounded-2xl focus:ring-0 focus:border-orange-500 focus:bg-white font-semibold transition-all" 
                                        placeholder="0.00">
                                </div>
                                <p class="text-[10px] text-gray-400 font-medium mt-1.5 pl-1">Boş bırakırsanız alt limit olmaz.</p>
                            </div>

                            <!-- Max Uses -->
                             <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kullanım Limiti</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-users text-gray-300 group-focus-within:text-orange-500 transition-colors"></i>
                                    </div>
                                    <input type="number" name="max_uses" value="{{ old('max_uses') }}"
                                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border-2 border-gray-100 text-gray-900 rounded-2xl focus:ring-0 focus:border-orange-500 focus:bg-white font-semibold transition-all" 
                                        placeholder="Sınırsız">
                                </div>
                                <p class="text-[10px] text-gray-400 font-medium mt-1.5 pl-1">Toplam kaç adet kullanılabilir?</p>
                            </div>

                            <!-- Expiry Date (Custom Calendar) -->
                             <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Son Kullanma Tarihi</label>
                                <div class="relative group" @click.away="showDatePicker = false">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-300 group-focus-within:text-orange-500 transition-colors"></i>
                                    </div>
                                    <div @click="showDatePicker = !showDatePicker" 
                                         class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border-2 border-gray-100 text-gray-900 rounded-2xl cursor-pointer flex items-center justify-between transition-all"
                                         :class="showDatePicker ? 'border-orange-500 ring-4 ring-orange-500/10 bg-white' : 'hover:bg-white hover:border-gray-300'">
                                        <span class="font-semibold text-sm" x-text="datePickerValue || 'Tarih Seçiniz...'" :class="!datePickerValue ? 'text-gray-400' : ''"></span>
                                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="showDatePicker ? 'rotate-180 text-orange-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <input type="hidden" name="expires_at" x-model="expires_at">

                                    <!-- Calendar Dropdown -->
                                    <div x-show="showDatePicker" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         class="absolute z-50 mb-3 bottom-full left-0 w-full bg-white rounded-2xl shadow-2xl border border-gray-100 p-5 origin-bottom-left"
                                         style="min-width: 300px;">
                                        
                                        <div class="flex items-center justify-between mb-4">
                                            <button type="button" @click="prevMonth()" class="p-2 hover:bg-gray-100 rounded-full text-gray-600 transition hover:text-indigo-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                            </button>
                                            <span class="text-lg font-bold text-gray-800" x-text="monthNames[month] + ' ' + year"></span>
                                            <button type="button" @click="nextMonth()" class="p-2 hover:bg-gray-100 rounded-full text-gray-600 transition hover:text-indigo-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-7 mb-2 border-b border-gray-100 pb-2">
                                            <template x-for="day in days">
                                                <div class="text-center text-[10px] uppercase font-bold text-gray-400 tracking-wider" x-text="day"></div>
                                            </template>
                                        </div>

                                        <div class="grid grid-cols-7 gap-1">
                                            <template x-for="blank in blankDays">
                                                <div class="h-10"></div>
                                            </template>
                                            <template x-for="date in noOfDays">
                                                <div @click="getDateValue(date)"
                                                     class="h-10 flex items-center justify-center text-sm font-medium rounded-lg cursor-pointer transition-all duration-200 relative group"
                                                     :class="{
                                                         'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 scale-105 z-10 font-bold': isSelected(date),
                                                         'hover:bg-gray-50 text-gray-700 hover:text-indigo-600 hover:scale-105': !isSelected(date) && !isPast(date),
                                                         'text-gray-300 cursor-not-allowed decoration-slice': isPast(date)
                                                     }">
                                                    <span x-text="date" class="relative z-10"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a href="{{ route('vendor.coupons.index') }}" class="px-8 py-4 bg-white text-gray-500 border-2 border-gray-100 rounded-2xl font-bold hover:bg-gray-50 hover:text-gray-800 transition-all">İptal</a>
                        <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2">
                            <span>Kuponu Yayınla</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>

                </div>

                <!-- RIGHT COLUMN: LIVE PREVIEW -->
                <div class="lg:col-span-5 relative hidden lg:block">
                     <div class="sticky top-32">
                        <div class="relative mb-6">
                            <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">Canlı Önizleme</h3>
                            <p class="text-xs text-gray-400 mt-1">Müşterileriniz kuponu böyle görecek.</p>
                        </div>

                        <!-- Coupon Card Preview -->
                        <div class="relative w-full aspect-[1.8/1] bg-gradient-to-br from-violet-600 to-indigo-700 rounded-3xl shadow-2xl shadow-indigo-500/30 overflow-hidden text-white p-8 flex flex-col justify-between transform transition-all hover:scale-[1.02] duration-500">
                             
                             <!-- Background Decorations -->
                             <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                             <div class="absolute bottom-0 left-0 w-48 h-48 bg-purple-500/20 rounded-full blur-2xl -ml-10 -mb-10 pointer-events-none"></div>

                             <!-- Content -->
                             <div class="relative z-10">
                                 <div class="flex justify-between items-start">
                                     <div class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-lg border border-white/10">
                                         <span class="text-[10px] font-bold uppercase tracking-widest text-white/90">İndirim Kuponu</span>
                                     </div>
                                     <div class="text-2xl opacity-80">
                                         <i class="fas fa-gift"></i>
                                     </div>
                                 </div>

                                 <div class="mt-8 text-center">
                                     <h2 class="text-5xl font-black tracking-tighter drop-shadow-lg" x-text="type === 'percentage' ? '%' + (value || '0') : '₺' + (value || '0')">
                                         %0
                                     </h2>
                                     <p class="text-indigo-100 font-medium text-sm mt-1 uppercase tracking-wide opacity-80">İNDİRİM</p>
                                 </div>
                             </div>

                             <!-- Footer -->
                             <div class="relative z-10 pt-6 mt-6 border-t border-white/10 flex items-center justify-between">
                                 <div>
                                     <p class="text-[10px] text-indigo-200 uppercase font-bold tracking-wider mb-1">Kupon Kodu</p>
                                     <p class="font-mono text-xl font-bold tracking-wider" x-text="code ? code.toUpperCase() : 'KOD-YOK'"></p>
                                 </div>
                                 <div class="text-right">
                                     <p class="text-[10px] text-indigo-200 uppercase font-bold tracking-wider mb-1">Geçerlilik</p>
                                     <p class="text-xs font-bold" x-text="datePickerValue || 'Süresiz'"></p>
                                 </div>
                             </div>

                             <!-- Dashed / Serrated Edge Effect (CSS Trick) -->
                             <div class="absolute top-1/2 -left-3 w-6 h-6 bg-gray-50 rounded-full"></div>
                             <div class="absolute top-1/2 -right-3 w-6 h-6 bg-gray-50 rounded-full"></div>
                        </div>

                        <!-- Info Box -->
                        <div class="mt-8 bg-blue-50 rounded-2xl p-5 border border-blue-100 flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex-shrink-0 flex items-center justify-center text-lg">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-blue-900">İpucu</h4>
                                <p class="text-xs text-blue-700 mt-1 leading-relaxed">
                                    Kupon kodlarınızı kısa ve akılda kalıcı yapın. "YAZ20", "HOSGELDIN" gibi kodlar daha fazla kullanım oranına sahiptir.
                                </p>
                            </div>
                        </div>

                     </div>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('couponForm', (initialData) => ({
            code: initialData.code || '',
            type: initialData.type || 'percentage',
            value: initialData.value || '',
            min_amount: initialData.min_amount || '',
            expires_at: initialData.expires_at || '',
            
            // Calendar Data
            showDatePicker: false,
            datePickerValue: '',
            month: new Date().getMonth(),
            year: new Date().getFullYear(),
            noOfDays: [],
            blankDays: [],
            days: ['Pt', 'Sa', 'Ça', 'Pe', 'Cu', 'Ct', 'Pa'],
            monthNames: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],

            init() {
                // Initialize calendar
                if(this.expires_at) {
                    const d = new Date(this.expires_at);
                    this.month = d.getMonth();
                    this.year = d.getFullYear();
                    this.datePickerValue = d.toLocaleDateString('tr-TR', { day: 'numeric', month: 'long', year: 'numeric' });
                }
                this.getDays();
            },

            // Calendar Methods
            getDays() {
                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                let dayOfWeek = new Date(this.year, this.month).getDay();
                let blankdaysArray = [];
                let adjustedDay = dayOfWeek === 0 ? 6 : dayOfWeek - 1; // Mon=0, Sun=6

                for (var i = 1; i <= adjustedDay; i++) {
                    blankdaysArray.push(i);
                }

                let daysArray = [];
                for (var i = 1; i <= daysInMonth; i++) {
                    daysArray.push(i);
                }

                this.blankDays = blankdaysArray;
                this.noOfDays = daysArray;
            },

            prevMonth() {
                if (this.month === 0) {
                    this.month = 11;
                    this.year--;
                } else {
                    this.month--;
                }
                this.getDays();
            },

            nextMonth() {
                if (this.month === 11) {
                    this.month = 0;
                    this.year++;
                } else {
                    this.month++;
                }
                this.getDays();
            },

            isPast(date) {
                const d = new Date(this.year, this.month, date);
                const today = new Date();
                today.setHours(0,0,0,0);
                return d < today;
            },

            isSelected(date) {
                if (!this.expires_at) return false;
                const d = new Date(this.year, this.month, date);
                const sel = new Date(this.expires_at);
                return d.getFullYear() === sel.getFullYear() && 
                       d.getMonth() === sel.getMonth() && 
                       d.getDate() === sel.getDate();
            },

            getDateValue(date) {
                if(this.isPast(date)) return;

                let d = new Date(this.year, this.month, date);
                d.setHours(12,0,0,0); // Timezone issues prevention, set to noon
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const dayStr = String(d.getDate()).padStart(2, '0');
                
                this.expires_at = `${y}-${m}-${dayStr}`;
                this.datePickerValue = d.toLocaleDateString('tr-TR', { day: 'numeric', month: 'long', year: 'numeric' });
                this.showDatePicker = false;
            }
        }));
    });
</script>
@endsection
