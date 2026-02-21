<!-- Navbar Component - RezerVist Premium -->
<nav 
    x-data="{ 
        scrolled: false, 
        mobileMenu: false, 
        notificationsOpen: false,
        activeMenu: null
    }" 
    @scroll.window="scrolled = (window.pageYOffset > 50)"
    :class="{
        'py-6 bg-transparent': !scrolled,
        'py-3 bg-white/90 backdrop-blur-2xl border-b border-purple-500/10 shadow-[0_10px_40px_-15px_rgba(124,58,237,0.1)]': scrolled
    }"
    class="fixed top-0 left-0 w-full z-[200] transition-all duration-700 ease-[cubic-bezier(0.23,1,0.32,1)]"
>
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
        <div class="flex items-center justify-between">
            
            <!-- Logo Section -->
            <div class="flex items-center gap-14">
                <a href="/" class="relative group flex items-center gap-3">
                    <div 
                        class="relative w-12 h-12 flex items-center justify-center transition-all duration-500 group-hover:scale-110"
                        :class="scrolled ? 'scale-90' : 'scale-100'"
                    >
                        <!-- Floating Background Layers -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#7C3AED] to-[#A855F7] rounded-2xl rotate-6 group-hover:rotate-12 transition-transform duration-500 opacity-20 blur-lg"></div>
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#7C3AED] to-[#A855F7] rounded-[1.2rem] shadow-xl shadow-purple-500/20"></div>
                        <span class="relative text-white font-black text-2xl tracking-tighter">{{ substr($globalSettings['site_name'], 0, 1) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span 
                            class="text-2xl font-black tracking-tight transition-colors duration-300"
                            :class="scrolled ? 'text-gray-900' : 'text-gray-900'"
                        >
                            {{ $globalSettings['site_name'] }}
                        </span>
                        <div class="h-0.5 w-0 group-hover:w-full bg-[#7C3AED] transition-all duration-500 rounded-full"></div>
                    </div>
                </a>

                <!-- Desktop Navigation Menu -->
                <div class="hidden xl:flex items-center gap-3">
                    <!-- Discover (Keşfet) Megamenu Trigger -->
                    <div class="relative group h-14 flex items-center" @mouseenter="activeMenu = 'discover'" @mouseleave="activeMenu = null">
                        <button 
                            class="px-5 py-2.5 rounded-2xl flex items-center gap-2 group transition-all duration-500"
                            :class="activeMenu === 'discover' ? 'bg-purple-50 text-[#7C3AED]' : 'text-gray-600 hover:bg-gray-50'"
                        >
                            <span class="text-[14px] font-black uppercase tracking-wider">Keşfet</span>
                            <svg 
                                class="w-4 h-4 transition-transform duration-500" 
                                :class="activeMenu === 'discover' ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Megamenu Dropdown -->
                        <div 
                            x-show="activeMenu === 'discover'"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                            class="absolute top-full -left-20 w-[900px] bg-white/95 backdrop-blur-3xl rounded-[2.5rem] shadow-[0_50px_100px_-20px_rgba(124,58,237,0.15)] border border-white/50 p-10 mt-2 z-[210] overflow-hidden"
                            style="display: none;"
                        >
                            <!-- Decorative background shapes -->
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-purple-100/50 rounded-full blur-3xl opacity-50"></div>
                            
                            <div class="relative grid grid-cols-12 gap-12">
                                <!-- Categories Side (Column 1) -->
                                <div class="col-span-4">
                                    <div class="flex items-center gap-3 mb-8">
                                        <div class="w-1 h-6 bg-[#7C3AED] rounded-full"></div>
                                        <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">Kategoriler</h4>
                                    </div>
                                    <div class="grid gap-2">
                                        @forelse($navbarCategories as $category)
                                            <a href="/search?category={{ $category->slug }}" class="group/cat flex items-center gap-5 p-4 rounded-3xl hover:bg-white hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-500 border border-transparent hover:border-purple-100">
                                                <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover/cat:bg-[#7C3AED] group-hover/cat:text-white group-hover/cat:rotate-6 transition-all duration-500 shadow-sm">
                                                    @if($category->icon)
                                                        <i class="{{ $category->icon }} text-xl"></i>
                                                    @else
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-black text-gray-900 text-[15px] group-hover/cat:text-[#7C3AED] transition-colors tracking-tight">{{ $category->name }}</p>
                                                    <p class="text-[11px] text-gray-400 font-bold tracking-wide mt-0.5">Mükemmel Deneyim</p>
                                                </div>
                                            </a>
                                        @empty
                                            <p class="text-xs text-gray-400 p-4">Kategori bulunamadı.</p>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Cuisines Side (Column 2) -->
                                <div class="col-span-8">
                                    <div class="flex items-center gap-3 mb-10">
                                        <div class="w-1 h-6 bg-[#A855F7] rounded-full"></div>
                                        <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">Popüler Mutfaklar</h4>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        @forelse($navbarCuisines as $cuisine)
                                            <a href="/search?cuisine={{ $cuisine->slug }}" class="group/cuisine flex items-center justify-between p-5 rounded-[2rem] bg-gray-50/50 hover:bg-gradient-to-tr hover:from-[#7C3AED] hover:to-[#A855F7] transition-all duration-500 border border-gray-100 hover:border-transparent hover:shadow-xl hover:shadow-purple-500/20">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center group-hover/cuisine:bg-white/20 transition-all duration-500">
                                                        <span class="w-2 h-2 rounded-full bg-[#7C3AED] group-hover/cuisine:bg-white animate-pulse"></span>
                                                    </div>
                                                    <span class="text-[14px] font-black text-gray-800 group-hover/cuisine:text-white transition-colors tracking-tight">{{ $cuisine->name }}</span>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-300 group-hover:text-white/60 transform group-hover/cuisine:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        @empty
                                            <p class="col-span-2 text-xs text-gray-400">Mutfak bulunamadı.</p>
                                        @endforelse
                                    </div>
                                    
                                    <!-- Featured CTA -->
                                    <div class="mt-12 p-8 rounded-[2.5rem] bg-gradient-to-br from-gray-900 to-gray-800 relative overflow-hidden group/cta">
                                        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#7C3AED] rounded-full blur-[80px] opacity-30 group-hover/cta:scale-150 transition-transform duration-1000"></div>
                                        <div class="relative flex items-center justify-between">
                                            <div>
                                                <h5 class="text-white font-black text-2xl mb-2 tracking-tight">Hala Karar Veremedin mi?</h5>
                                                <p class="text-gray-400 text-sm font-bold tracking-wide">Yüzlerce seçkin mekan seni bekliyor.</p>
                                            </div>
                                            <a href="/search" class="px-8 py-4 bg-white text-gray-900 rounded-2xl font-black text-sm hover:bg-[#7C3AED] hover:text-white transition-all duration-500 shadow-xl">Hemen Keşfet</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Direct Links -->
                    @php
                        $navLinks = [
                            ['label' => __('common.menu.blog'), 'route' => 'blog.index', 'active' => 'blog.*'],
                            ['label' => 'Kampanyalar', 'route' => 'pages.campaigns', 'active' => 'pages.campaigns'],
                            ['label' => 'İletişim', 'route' => 'pages.contact', 'active' => 'pages.contact'],
                        ];
                    @endphp

                    @foreach($navLinks as $link)
                        <a 
                            href="{{ route($link['route']) }}" 
                            class="px-5 py-2.5 rounded-2xl text-[14px] font-black uppercase tracking-wider transition-all duration-500 border border-transparent"
                            :class="{{ request()->routeIs($link['active']) ? 'true' : 'false' }} ? 'bg-[#7C3AED] text-white shadow-lg shadow-purple-500/25' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7C3AED]'"
                        >
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Right Side: Actions -->
            <div class="flex items-center gap-4">
                @auth
                    <!-- Search Trigger (Global Keybind) -->
                    @if(Auth::user()->role === 'business')
                    <button 
                        @click="window.dispatchEvent(new CustomEvent('toggle-vendor-search'))"
                        class="hidden md:flex items-center gap-4 px-5 py-2.5 bg-gray-50/50 rounded-2xl border border-gray-100/50 hover:bg-white hover:border-purple-200 transition-all duration-500 group shadow-sm"
                    >
                        <i class="fa fa-magnifying-glass text-gray-400 group-hover:text-[#7C3AED] transition-colors"></i>
                        <span class="text-xs font-black text-gray-400 group-hover:text-[#7C3AED] tracking-widest uppercase">Ara...</span>
                        <div class="flex items-center gap-1">
                            <span class="text-[9px] font-black text-gray-300 bg-white px-2 py-0.5 rounded-lg border border-gray-100 tracking-tighter shadow-sm group-hover:border-purple-200">⌘</span>
                            <span class="text-[9px] font-black text-gray-300 bg-white px-2 py-0.5 rounded-lg border border-gray-100 tracking-tighter shadow-sm group-hover:border-purple-200">K</span>
                        </div>
                    </button>
                    @endif

                    <!-- Notifications -->
                    <div class="relative">
                        <button 
                            @click="notificationsOpen = !notificationsOpen"
                            class="w-12 h-12 flex items-center justify-center rounded-2xl transition-all duration-500 relative group overflow-hidden"
                            :class="notificationsOpen ? 'bg-[#7C3AED] text-white' : 'bg-gray-50/80 hover:bg-white text-gray-400 shadow-sm border border-gray-100'"
                        >
                            <i class="fa-regular fa-bell text-lg group-hover:rotate-12 transition-transform"></i>
                            @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="absolute top-3 right-3 w-4 h-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center border-2 border-white shadow-sm ring-2 ring-red-500/20">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>
                    </div>

                    <!-- User Profile -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open" 
                            class="flex items-center gap-3 p-1 rounded-2xl transition-all duration-500 bg-white shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-purple-500/10 hover:border-purple-200 group"
                        >
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#7C3AED] to-[#A855F7] flex items-center justify-center text-white font-black text-sm shadow-md group-hover:rotate-3 transition-transform">
                                {{ Auth::user()->initials }}
                            </div>
                            <div class="hidden md:block text-left pr-2">
                                <p class="text-[10px] font-black text-[#7C3AED] uppercase tracking-[0.2em] leading-none mb-1">{{ Auth::user()->role }}</p>
                                <p class="text-[13px] font-black text-gray-900 leading-none truncate max-w-[100px]">{{ Auth::user()->name }}</p>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-300 mr-2 transition-transform duration-500" :class="open ? 'rotate-180 text-purple-400' : ''"></i>
                        </button>
                        
                        <!-- Profile Dropdown -->
                        <div 
                            x-show="open" 
                            @click.away="open = false" 
                            x-transition:enter="transition duration-300 cubic-bezier(0.23, 1, 0.32, 1)"
                            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            class="absolute right-0 mt-4 w-72 bg-white rounded-[2rem] shadow-[0_40px_80px_-15px_rgba(0,0,0,0.15)] border border-gray-100 py-4 z-[250] overflow-hidden"
                            style="display: none;"
                        >
                            <div class="px-8 py-4 border-b border-gray-50 mb-3 bg-gray-50/50">
                                <p class="text-[10px] font-black text-purple-400 uppercase tracking-[0.2em] mb-1">Hesabım</p>
                                <p class="text-sm font-black text-gray-900 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            
                            <a href="{{ route('profile.edit') }}" class="group/item flex items-center justify-between px-8 py-3 text-sm font-black text-gray-600 hover:text-[#7C3AED] hover:bg-purple-50 transition-all">
                                <div class="flex items-center gap-4">
                                    <i class="fa-regular fa-user opacity-40 group-hover/item:opacity-100 transition-opacity"></i>
                                    <span>Profil Ayarları</span>
                                </div>
                                <i class="fa-solid fa-chevron-right text-[10px] opacity-0 group-hover/item:opacity-100 translate-x-2 group-hover/item:translate-x-0 transition-all"></i>
                            </a>

                            <a href="{{ route('profile.reservations') }}" class="group/item flex items-center justify-between px-8 py-3 text-sm font-black text-gray-600 hover:text-[#7C3AED] hover:bg-purple-50 transition-all">
                                <div class="flex items-center gap-4">
                                    <i class="fa-regular fa-calendar opacity-40 group-hover/item:opacity-100 transition-opacity"></i>
                                    <span>Rezervasyonlarım</span>
                                </div>
                                <i class="fa-solid fa-chevron-right text-[10px] opacity-0 group-hover/item:opacity-100 translate-x-2 group-hover/item:translate-x-0 transition-all"></i>
                            </a>

                            <div class="h-px bg-gray-50 my-3 mx-4"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-4 px-8 py-4 text-sm font-black text-red-500 hover:bg-red-50 transition-all">
                                    <i class="fa-solid fa-arrow-right-from-bracket opacity-60"></i>
                                    Oturumu Kapat
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="px-6 py-3 text-[14px] font-black text-gray-600 hover:text-[#7C3AED] transition-all tracking-wider uppercase">Giriş</a>
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-[#7C3AED] text-white text-[14px] font-black rounded-2xl shadow-xl shadow-purple-500/30 hover:shadow-purple-500/50 hover:-translate-y-1 transition-all tracking-wider uppercase">Kayıt Ol</a>
                    </div>
                @endauth

                <!-- Mobile Menu Trigger -->
                <button 
                    @click="mobileMenu = !mobileMenu"
                    class="xl:hidden w-12 h-12 flex items-center justify-center bg-gray-900 text-white rounded-2xl transition-all duration-500 group relative overflow-hidden"
                >
                    <div class="relative z-10 space-y-1.5 transition-all duration-500" :class="mobileMenu ? 'rotate-90' : ''">
                        <span class="block w-6 h-0.5 bg-white transition-all duration-500" :class="mobileMenu ? 'rotate-45 translate-y-2' : ''"></span>
                        <span class="block w-4 h-0.5 bg-[#A855F7] transition-all duration-500" :class="mobileMenu ? 'opacity-0' : ''"></span>
                        <span class="block w-6 h-0.5 bg-white transition-all duration-500" :class="mobileMenu ? '-rotate-45 -translate-y-2' : ''"></span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#7C3AED] to-[#A855F7] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Overlay -->
    <div 
        x-show="mobileMenu"
        x-transition:enter="transition duration-500"
        x-transition:enter-start="opacity-0 scale-105"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition duration-300"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-105"
        class="fixed inset-0 bg-white/95 backdrop-blur-3xl z-[300] xl:hidden p-12 overflow-y-auto"
        style="display: none;"
    >
        <div class="max-w-xl mx-auto">
            <div class="flex items-center justify-between mb-20">
                <div class="w-12 h-12 bg-[#7C3AED] rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg">R</div>
                <button @click="mobileMenu = false" class="w-12 h-12 flex items-center justify-center bg-red-50 text-red-500 rounded-2xl hover:bg-red-500 hover:text-white transition-all">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <div class="space-y-4">
                <p class="text-[10px] font-black text-purple-400 uppercase tracking-[0.3em] mb-8">Ana Menü</p>
                <a href="/" class="block text-4xl font-black text-gray-900 hover:text-[#7C3AED] transition-colors tracking-tighter">Ana Sayfa</a>
                <a href="/search" class="block text-4xl font-black text-gray-900 hover:text-[#7C3AED] transition-colors tracking-tighter">Keşfet</a>
                <a href="{{ route('blog.index') }}" class="block text-4xl font-black text-gray-900 hover:text-[#7C3AED] transition-colors tracking-tighter">Duyurular</a>
                <a href="{{ route('pages.campaigns') }}" class="block text-4xl font-black text-gray-900 hover:text-[#7C3AED] transition-colors tracking-tighter">Fırsatlar</a>
                <a href="{{ route('pages.contact') }}" class="block text-4xl font-black text-gray-900 hover:text-[#7C3AED] transition-colors tracking-tighter">Bize Ulaş</a>
            </div>

            <div class="mt-20 pt-10 border-t border-gray-100">
                @guest
                    <div class="grid gap-4">
                        <a href="{{ route('login') }}" class="w-full py-5 bg-gray-50 rounded-2xl text-center font-black text-gray-900 hover:bg-gray-100 transition-all uppercase tracking-widest text-sm">Giriş Yap</a>
                        <a href="{{ route('register') }}" class="w-full py-5 bg-[#7C3AED] rounded-2xl text-center font-black text-white shadow-xl shadow-purple-500/30 uppercase tracking-widest text-sm">Katıl Bize</a>
                    </div>
                @else
                    <div class="flex items-center gap-6 p-6 bg-purple-50 rounded-[2rem]">
                        <div class="w-16 h-16 rounded-2xl bg-[#7C3AED] flex items-center justify-center text-white font-black text-xl">{{ Auth::user()->initials }}</div>
                        <div>
                            <p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">{{ Auth::user()->role }}</p>
                            <p class="text-lg font-black text-gray-900 leading-tight">{{ Auth::user()->name }}</p>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
