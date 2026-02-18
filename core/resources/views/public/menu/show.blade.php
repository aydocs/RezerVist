<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $business->name }} - Menü | Rezervist</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root { 
            --primary: #6200EE; 
            --primary-light: #7c4dff;
            --secondary: #03DAC6; 
            --bg: #F9FAFB; 
            --text-dark: #111827; 
            --text-light: #6B7280; 
        }
        
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: var(--bg); 
            color: var(--text-dark); 
            overflow-x: hidden; 
            scroll-behavior: smooth; 
        }

        /* Official Rezervist Glassmorphism */
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(229, 231, 235, 0.5); }
        
        /* Immersive Hero */
        .hero-section { height: 75vh; position: relative; overflow: hidden; }
        .hero-image { transform: scale(1.05); transition: transform 0.5s ease-out; }
        .hero-overlay { background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.1) 100%); }
        
        /* Animations */
        .reveal { opacity: 0; transform: translateY(20px); transition: all 0.6s cubic-bezier(0.2, 1, 0.3, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .stagger-item { opacity: 0; transform: translateY(15px); transition: all 0.4s ease-out; }
        .stagger-item.show { opacity: 1; transform: translateY(0); }

        /* Official Cards */
        .product-card { 
            border-radius: 1.5rem; 
            background: white; 
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .product-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08); 
            border-color: rgba(98, 0, 238, 0.1);
        }
        
        /* Category Styles */
        .category-item { transition: all 0.3s ease; position: relative; color: var(--text-light); font-weight: 600; }
        .category-item.active { color: var(--primary); }
        .category-item.active::after { 
            content: ''; 
            position: absolute; 
            bottom: -8px; 
            left: 50%; 
            transform: translateX(-50%); 
            width: 60%; 
            height: 3px; 
            background: var(--primary); 
            border-radius: 99px; 
            background: var(--primary); 
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(200%); }
        }
        
        .logo-box {
            background: linear-gradient(135deg, #6200EE 0%, #8e44ad 100%);
            box-shadow: 0 10px 20px rgba(98, 0, 238, 0.3);
        }
        
        /* Custom Checkbox for Guests */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
    </style>
</head>
<body class="pb-10">

    <!-- Official Preloader -->
    <div id="loader" class="fixed inset-0 z-[100] bg-slate-900 flex items-center justify-center transition-opacity duration-700">
        <div class="text-center">
            <div class="w-16 h-16 logo-box rounded-2xl flex items-center justify-center text-white text-3xl font-bold mb-4 mx-auto animate-bounce">
                {{ substr($business->name, 0, 1) }}
            </div>
            <h1 class="text-white text-2xl font-bold tracking-tight mb-2">{{ $business->name }}</h1>
            <div class="w-12 h-0.5 bg-indigo-500 mx-auto rounded-full overflow-hidden">
                <div class="h-full bg-white w-1/2 animate-[shimmer_2s_infinite]"></div>
            </div>
        </div>
    </div>

    <!-- Official Style Hero -->
    <section class="hero-section flex flex-col justify-end pb-24 md:pb-32" x-data="bookingWidget()">
        @php
            $cover = $business->images->first()?->url ?? 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80';
        @endphp
        <img src="{{ $cover }}" alt="{{ $business->name }}" class="hero-image absolute inset-0 w-full h-full object-cover z-0">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        
        <div class="relative z-20 w-full max-w-6xl mx-auto px-6 mb-12">
            <div class="reveal active" id="hero-reveal">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-white text-[10px] font-bold uppercase tracking-widest border border-white/10">
                        {{ $business->businessCategory->name ?? $business->category }}
                    </span>
                    @if($business->is_open)
                        <span class="px-3 py-1 bg-green-500/80 backdrop-blur-md rounded-full text-white text-[10px] font-bold uppercase tracking-widest">AÇIK</span>
                    @endif
                </div>
                <h1 class="text-white text-4xl md:text-6xl font-black leading-tight mb-8">
                    {{ $business->name }} <br> <span class="text-white/70 font-medium text-2xl md:text-3xl">Dijital Menü Deneyimi</span>
                </h1>
                
                <!-- NEW BOOKING SEARCH BAR -->
                <div class="bg-white rounded-[2rem] p-2 shadow-2xl flex flex-col md:flex-row items-center gap-2 md:gap-0 max-w-4xl animate-in zoom-in duration-500">
                    
                    <!-- Date Input -->
                    <div class="relative w-full md:w-1/3 group" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full h-20 bg-transparent hover:bg-gray-50 rounded-[1.5rem] px-6 text-left transition-colors flex items-center justify-between group-hover:bg-gray-50/80">
                            <div>
                                <p class="text-[10px] font-bold text-primary uppercase tracking-wider mb-1">TARİH</p>
                                <p class="font-bold text-gray-900 text-lg truncate" x-text="formatDate(form.date) || 'Tarih Seçin'"></p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-primary/5 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </button>

                        <!-- Calendar Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute top-full left-0 mt-4 w-80 bg-white rounded-3xl shadow-2xl border border-gray-100 p-6 z-50">
                            <!-- Simple Calendar Logic Here -->
                            <div class="flex items-center justify-between mb-4">
                                <button type="button" @click="prevMonth()" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center"><i class="fas fa-chevron-left text-xs"></i></button>
                                <span class="font-bold text-gray-900" x-text="monthNames[currentDate.getMonth()] + ' ' + currentDate.getFullYear()"></span>
                                <button type="button" @click="nextMonth()" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center"><i class="fas fa-chevron-right text-xs"></i></button>
                            </div>
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <template x-for="d in ['Pt','Sa','Ça','Pe','Cu','Ct','Pz']">
                                    <div class="text-center text-[10px] text-gray-400 font-bold" x-text="d"></div>
                                </template>
                            </div>
                            <div class="grid grid-cols-7 gap-1">
                                <template x-for="day in daysInMonth">
                                    <button @click="selectDate(day); open = false" 
                                        :disabled="isPastDate(day)"
                                        class="w-full aspect-square rounded-lg text-sm font-bold flex items-center justify-center transition-colors hover:bg-primary/10 hover:text-primary disabled:opacity-20 disabled:cursor-not-allowed"
                                        :class="isSelectedDate(day) ? 'bg-primary text-white hover:bg-primary hover:text-white' : 'text-gray-700'"
                                        x-text="day"></button>
                                </template> 
                            </div>
                        </div>
                    </div>

                    <div class="hidden md:block w-px h-12 bg-gray-100"></div>

                    <!-- Time Input -->
                    <div class="relative w-full md:w-1/3 group" x-data="{ open: false }">
                         <button @click="open = !open" class="w-full h-20 bg-transparent hover:bg-gray-50 rounded-[1.5rem] px-6 text-left transition-colors flex items-center justify-between group-hover:bg-gray-50/80">
                            <div>
                                <p class="text-[10px] font-bold text-primary uppercase tracking-wider mb-1">SAAT</p>
                                <p class="font-bold text-gray-900 text-lg truncate" x-text="form.time || 'Saat Seçin'"></p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-primary/5 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                <i class="fas fa-clock"></i>
                            </div>
                        </button>
                        
                        <!-- Time Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute top-full left-0 mt-4 w-60 bg-white rounded-3xl shadow-2xl border border-gray-100 p-4 z-50 h-64 overflow-y-auto no-scrollbar">
                           <div class="grid grid-cols-2 gap-2">
                               <template x-for="t in timeSlots">
                                   <button @click="form.time = t; open = false" 
                                        class="py-2 rounded-xl text-sm font-bold hover:bg-primary/10 hover:text-primary transition-colors text-center"
                                        :class="form.time === t ? 'bg-primary text-white hover:bg-primary hover:text-white' : 'text-gray-700 bg-gray-50'"
                                        x-text="t"></button>
                               </template>
                           </div>
                        </div>
                    </div>

                    <div class="hidden md:block w-px h-12 bg-gray-100"></div>

                    <!-- Guests Input -->
                     <div class="relative w-full md:w-1/3 group" x-data="{ open: false }">
                         <button @click="open = !open" class="w-full h-20 bg-transparent hover:bg-gray-50 rounded-[1.5rem] px-6 text-left transition-colors flex items-center justify-between group-hover:bg-gray-50/80">
                            <div>
                                <p class="text-[10px] font-bold text-primary uppercase tracking-wider mb-1">KİŞİ SAYISI</p>
                                <p class="font-bold text-gray-900 text-lg truncate" x-text="form.guests + ' Kişi'"></p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-primary/5 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                <i class="fas fa-user-friends"></i>
                            </div>
                        </button>

                         <!-- Guests Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute top-full right-0 mt-4 w-72 bg-white rounded-3xl shadow-2xl border border-gray-100 p-6 z-50">
                            <div class="flex items-center justify-between mb-4">
                                <span class="font-bold text-gray-900">Yetişkin</span>
                                <div class="flex items-center gap-3">
                                    <button @click="form.guests > 1 ? form.guests-- : null" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200"><i class="fas fa-minus text-xs"></i></button>
                                    <span class="font-bold w-6 text-center" x-text="form.guests"></span>
                                    <button @click="form.guests < 20 ? form.guests++ : null" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200"><i class="fas fa-plus text-xs"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button @click="submitBooking()" 
                        class="w-full md:w-auto h-20 md:h-full aspect-square rounded-[1.5rem] bg-primary hover:bg-primary-light text-white flex flex-col items-center justify-center gap-1 shadow-lg shadow-primary/30 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed ml-2"
                        :disabled="!form.date || !form.time">
                        <i class="fas fa-search text-xl"></i>
                    </button>

                </div>
            </div>
        </div>

        <a href="{{ route('business.show', $business->slug) }}" class="absolute top-6 right-6 w-10 h-10 bg-black/40 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-black/60 transition-all z-20">
            <i class="fas fa-times"></i>
        </a>
    </section>

    <!-- JS for Booking Widget -->
    <script>
        function bookingWidget() {
            return {
                currentDate: new Date(),
                form: {
                    date: null,
                    time: null,
                    guests: 2
                },
                timeSlots: ['09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00'],
                monthNames: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],
                
                get daysInMonth() {
                    const year = this.currentDate.getFullYear();
                    const month = this.currentDate.getMonth();
                    const days = new Date(year, month + 1, 0).getDate();
                    return Array.from({length: days}, (_, i) => i + 1);
                },

                prevMonth() {
                    this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1);
                },

                nextMonth() {
                    this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1);
                },

                isPastDate(day) {
                    const checkDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), day);
                    const today = new Date();
                    today.setHours(0,0,0,0);
                    return checkDate < today;
                },

                isSelectedDate(day) {
                    if(!this.form.date) return false;
                    const d = new Date(this.form.date);
                    return d.getDate() === day && d.getMonth() === this.currentDate.getMonth() && d.getFullYear() === this.currentDate.getFullYear();
                },

                selectDate(day) {
                    const date = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), day);
                    const offset = date.getTimezoneOffset(); 
                    const localDate = new Date(date.getTime() - (offset*60*1000)); 
                    this.form.date = localDate.toISOString().split('T')[0];
                },

                formatDate(dateStr) {
                    if(!dateStr) return null;
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('tr-TR', { day: 'numeric', month: 'long' });
                },

                submitBooking() {
                    if(!this.form.date || !this.form.time) return;
                    // Redirect to checkout with params
                    const url = "{{ route('booking.checkout', $business->slug) }}";
                    const params = new URLSearchParams({
                        date: this.form.date,
                        time: this.form.time, 
                        guests: this.form.guests
                    });
                    window.location.href = `${url}?${params.toString()}`;
                }
            }
        }
    </script>

    <!-- Official Brand Navigation -->
    <nav id="main-nav" class="sticky top-0 z-50 glass">
        <div class="max-w-6xl mx-auto flex items-center justify-between px-6 h-18">
            <div class="flex items-center gap-6 overflow-x-auto no-scrollbar scroll-smooth flex-1 py-4">
                @foreach($menuGroups as $category => $items)
                    <a href="#cat-{{ Str::slug($category) }}" 
                        class="category-item flex-shrink-0 text-sm whitespace-nowrap">
                        {{ mb_strtoupper($category) }}
                    </a>
                @endforeach
            </div>
            <div class="flex items-center gap-2 pl-4">
                <button id="search-toggle" class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-100 transition">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('booking.checkout', $business->slug) }}" class="logo-box text-white text-[11px] font-bold px-5 py-2.5 rounded-xl hover:shadow-xl transition-all uppercase tracking-wide hidden sm:block">
                    Masa Ayır
                </a>
            </div>
        </div>
        
        <!-- Search -->
        <div id="search-container" class="hidden bg-gray-50/80 backdrop-blur-md px-6 py-4 border-b border-gray-100">
            <div class="max-w-3xl mx-auto relative">
                <input type="text" id="menu-search-input" placeholder="Neye bakmıştınız?" 
                    class="w-full bg-white border border-gray-200 rounded-2xl px-12 py-3.5 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-sm font-medium">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </nav>

    <!-- Menu Content -->
    <main class="max-w-6xl mx-auto px-6 mt-12">
        @forelse($menuGroups as $category => $items)
            <section id="cat-{{ Str::slug($category) }}" class="menu-section mb-16 scroll-mt-24">
                <div class="flex items-center gap-4 mb-8 reveal">
                    <div class="w-1 h-8 bg-primary rounded-full"></div>
                    <div>
                        <h3 class="text-2xl font-extrabold text-gray-900">{{ $category }}</h3>
                        <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mt-0.5">{{ $items->count() }} Seçenek</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($items as $item)
                        <div class="product-card stagger-item group cursor-pointer" 
                             data-name="{{ strtolower($item->name) }}"
                             onclick="showProduct('{{ $item->name }}', '{{ $item->description }}', '{{ number_format($item->price, 2) }}', '{{ $item->image ? asset('storage/' . $item->image) : '' }}')">
                            
                            @if($item->image)
                                <div class="h-48 w-full overflow-hidden relative rounded-t-[1.5rem]">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    <div class="absolute bottom-4 right-4 px-3 py-1.5 bg-white/90 backdrop-blur-md rounded-xl shadow-lg border border-white/20">
                                        <span class="font-bold text-gray-900 text-sm">{{ number_format($item->price, 2) }} ₺</span>
                                    </div>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors leading-snug">{{ $item->name }}</h4>
                                    @if(!$item->image)
                                        <span class="font-bold text-primary">{{ number_format($item->price, 2) }} ₺</span>
                                    @endif
                                </div>
                                <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-2">{{ $item->description }}</p>
                                <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">İncele</span>
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-primary group-hover:text-white transition-all">
                                        <i class="fas fa-plus text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="py-24 text-center reveal">
                <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-300">
                    <i class="fas fa-utensils text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Henüz Menü Eklenmemiş</h3>
                <p class="text-gray-500 text-sm max-w-xs mx-auto mb-8">Bu işletmenin menüsü çok yakında burada olacak.</p>
                <a href="{{ route('business.show', $business->slug) }}" class="text-primary font-bold text-sm inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Geri Dön
                </a>
            </div>
        @endforelse
    </main>

    <!-- Modal -->
    <div id="product-modal" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="hideProduct()"></div>
        <div class="modal-content relative bg-white w-full max-w-lg rounded-[2.5rem] overflow-hidden shadow-2xl">
            <button onclick="hideProduct()" class="absolute top-5 right-5 w-9 h-9 bg-black/10 hover:bg-black/20 rounded-full flex items-center justify-center text-gray-800 z-20 transition">
                <i class="fas fa-times text-sm"></i>
            </button>
            <div id="modal-image-container" class="h-64 w-full overflow-hidden">
                <img id="modal-image" src="" alt="" class="w-full h-full object-cover">
            </div>
            <div class="p-8">
                <div class="flex justify-between items-start mb-4">
                    <h3 id="modal-title" class="text-2xl font-extrabold text-gray-900"></h3>
                    <div class="text-xl font-black text-primary"><span id="modal-price"></span> ₺</div>
                </div>
                <p id="modal-description" class="text-gray-500 text-sm leading-relaxed mb-8"></p>
                <div class="flex gap-4">
                    <button class="flex-1 logo-box text-white font-bold py-4 rounded-2xl shadow-lg hover:brightness-110 active:scale-95 transition-all">
                        SEPETE EKLE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Bar -->
    <div class="fixed bottom-6 left-6 right-6 z-40 sm:hidden">
        <a href="{{ route('booking.checkout', $business->slug) }}" 
            class="logo-box text-white w-full h-16 rounded-2xl font-bold flex items-center justify-between px-6 shadow-2xl active:scale-95 transition-all">
            <div class="flex items-center gap-3">
                <i class="fas fa-calendar-alt"></i>
                <span class="text-sm tracking-wide">MASA REZERVASYONU</span>
            </div>
            <i class="fas fa-chevron-right text-xs opacity-50"></i>
        </a>
    </div>

    <script>
        const loader = document.getElementById('loader');
        const modal = document.getElementById('product-modal');
        
        function hideLoader() {
            if (!loader || loader.classList.contains('hidden')) return;
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.classList.add('hidden');
                initReveal();
            }, 700);
        }

        setTimeout(hideLoader, 2000);
        window.addEventListener('load', hideLoader);

        function initReveal() {
            const options = { threshold: 0.1 };
            const revealObs = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
            }, options);

            const staggerObs = new IntersectionObserver((entries) => {
                entries.forEach((e, i) => { if (e.isIntersecting) setTimeout(() => e.target.classList.add('show'), 50 * (i % 3)); });
            }, options);

            document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));
            document.querySelectorAll('.stagger-item').forEach(el => staggerObs.observe(el));
            const hero = document.getElementById('hero-reveal');
            if (hero) hero.classList.add('active');
        }

        // Search
        const searchBox = document.getElementById('search-container');
        document.getElementById('search-toggle').addEventListener('click', () => {
            searchBox.classList.toggle('hidden');
            if(!searchBox.classList.contains('hidden')) document.getElementById('menu-search-input').focus();
        });

        const searchInput = document.getElementById('menu-search-input');
        searchInput.addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase().trim();
            document.querySelectorAll('.product-card').forEach(card => {
                const match = card.getAttribute('data-name').includes(q);
                card.style.display = match ? 'block' : 'none';
            });
            document.querySelectorAll('.menu-section').forEach(sec => {
                const vis = sec.querySelectorAll('.product-card[style="display: block;"]').length > 0;
                sec.style.display = vis || q === "" ? 'block' : 'none';
            });
        });

        // Modal
        function showProduct(name, desc, price, img) {
            document.getElementById('modal-title').innerText = name;
            document.getElementById('modal-description').innerText = desc || "Detaylı açıklama bulunmamaktadır.";
            document.getElementById('modal-price').innerText = price;
            const mImg = document.getElementById('modal-image');
            const mImgC = document.getElementById('modal-image-container');
            if(img) { mImg.src = img; mImgC.style.display = 'block'; } else { mImgC.style.display = 'none'; }
            modal.classList.remove('hidden');
            setTimeout(() => { document.body.classList.add('modal-active'); }, 10);
        }

        function hideProduct() {
            modal.classList.add('hidden');
            document.body.classList.remove('modal-active');
        }

        // Nav Active
        const navItems = document.querySelectorAll('.category-item');
        const sections = document.querySelectorAll('.menu-section');
        window.addEventListener('scroll', () => {
            let current = "";
            sections.forEach(sec => { if (pageYOffset >= sec.offsetTop - 150) current = sec.getAttribute('id'); });
            navItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === '#' + current) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
