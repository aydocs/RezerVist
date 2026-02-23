<!-- Mini Profile Card -->
<div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden mb-6 relative group">
    <div class="h-24 bg-gradient-to-br from-primary via-purple-600 to-indigo-600"></div>
    <div class="px-6 pb-6 text-center -mt-12 relative z-10">
        <div class="relative inline-block">
            @if(Auth::user()->profile_photo_path)
                <img src="{{ Auth::user()->profile_photo_url }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover bg-white">
            @else
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-3xl font-bold text-primary border-4 border-white shadow-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            @endif
            <button @click="editingPhoto = !editingPhoto" type="button" class="absolute bottom-0 right-0 bg-gray-900 text-white p-2 rounded-full shadow-lg border-2 border-white hover:bg-black transition transform hover:scale-105" title="Fotoğrafı Değiştir">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </button>
        </div>
        <h2 class="font-bold text-xl text-gray-900 mt-3">{{ Auth::user()->name }}</h2>
        <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
    </div>
</div>

<!-- Navigation Menu -->
<nav class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 p-3 space-y-1">
    <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Hesap</div>
    
    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('profile.edit') ? 'bg-primary/5 text-primary font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} rounded-2xl transition-all group">
        <div class="w-8 h-8 rounded-full {{ request()->routeIs('profile.edit') ? 'bg-primary/10 text-primary' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }} flex items-center justify-center mr-3 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        Profil Bilgilerim
    </a>

    <a href="{{ route('profile.reservations') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('profile.reservations') ? 'bg-primary/5 text-primary font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} rounded-2xl transition-all group">
        <div class="w-8 h-8 rounded-full {{ request()->routeIs('profile.reservations') ? 'bg-primary/10 text-primary' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }} flex items-center justify-center mr-3 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        Rezervasyonlarım
    </a>

    <a href="{{ route('profile.favorites') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('profile.favorites') ? 'bg-primary/5 text-primary font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} rounded-2xl transition-all group">
        <div class="w-8 h-8 rounded-full {{ request()->routeIs('profile.favorites') ? 'bg-primary/10 text-primary' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }} flex items-center justify-center mr-3 transition">
            <i class="fa-solid fa-heart text-[12px]"></i>
        </div>
        Favorilerim
    </a>

    <a href="{{ route('profile.referrals') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('profile.referrals') ? 'bg-primary/5 text-primary font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} rounded-2xl transition-all group">
        <div class="w-8 h-8 rounded-full {{ request()->routeIs('profile.referrals') ? 'bg-primary/10 text-primary' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }} flex items-center justify-center mr-3 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        Arkadaşını Davet Et
    </a>

    <a href="{{ route('profile.wallet.index') }}" class="flex items-center px-4 py-3.5 {{ request()->is('profile/wallet*') ? 'bg-primary/5 text-primary font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} rounded-2xl transition-all group">
        <div class="w-8 h-8 rounded-full {{ request()->is('profile/wallet*') ? 'bg-primary/10 text-primary' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }} flex items-center justify-center mr-3 transition">
            <i class="fa-solid fa-credit-card"></i>
        </div>
        Cüzdanım
    </a>

    <a href="{{ request()->routeIs('profile.edit') ? '#notifications' : route('profile.edit') . '#notifications' }}" class="flex items-center px-4 py-3.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium rounded-2xl transition-all group">
        <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600 flex items-center justify-center mr-3 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        </div>
        Bildirim Ayarları
    </a>

    <a href="{{ route('messages.index') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('messages.*') ? 'bg-primary/5 text-primary font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} rounded-2xl transition-all group">
        <div class="w-8 h-8 rounded-full {{ request()->routeIs('messages.*') ? 'bg-primary/10 text-primary' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }} flex items-center justify-center mr-3 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        </div>
        Mesajlarım
    </a>

    <a href="{{ route('profile.support') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('profile.support') ? 'bg-primary/5 text-primary font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} rounded-2xl transition-all group">
        <div class="w-8 h-8 rounded-full {{ request()->routeIs('profile.support') ? 'bg-primary/10 text-primary' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }} flex items-center justify-center mr-3 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        Destek Taleplerim
    </a>

    @if(Auth::user()->role === 'business')
        <div class="border-t border-gray-100/50 my-2"></div>
        <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">İşletme Yönetimi</div>
        
        <a href="{{ route('vendor.dashboard') }}" class="flex items-center px-4 py-3.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium rounded-2xl transition-all group">
            <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600 flex items-center justify-center mr-3 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            İşletme Paneli
        </a>
        <a href="{{ route('vendor.reservations.index') }}" class="flex items-center px-4 py-3.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium rounded-2xl transition-all group">
            <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600 flex items-center justify-center mr-3 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            Rezervasyonlar
        </a>
        <a href="{{ route('vendor.menus.index') }}" class="flex items-center px-4 py-3.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium rounded-2xl transition-all group">
            <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600 flex items-center justify-center mr-3 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path></svg>
            </div>
            Menü & Hizmetler
        </a>
        <a href="{{ route('vendor.finance.index') }}" class="flex items-center px-4 py-3.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium rounded-2xl transition-all group">
            <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600 flex items-center justify-center mr-3 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            Finansal Raporlar
        </a>
    @endif

    @if(Auth::user()->role === 'admin')
        <div class="border-t border-gray-100/50 my-2"></div>
        <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Yönetim</div>
        
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium rounded-2xl transition-all group">
            <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600 flex items-center justify-center mr-3 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
            Yönetim Paneli
        </a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium rounded-2xl transition-all group">
            <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600 flex items-center justify-center mr-3 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            Kullanıcı Yönetimi
        </a>
        <a href="{{ route('admin.applications.index') }}" class="flex items-center px-4 py-3.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium rounded-2xl transition-all group">
            <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600 flex items-center justify-center mr-3 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            Başvurular
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('admin.reviews.*') ? 'bg-primary/5 text-primary font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} rounded-2xl transition-all group">
            <div class="w-8 h-8 rounded-full {{ request()->routeIs('admin.reviews.*') ? 'bg-primary/10 text-primary' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }} flex items-center justify-center mr-3 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </div>
            Yorum Moderasyonu
        </a>
    @endif

    <div class="pt-2 mt-2 border-t border-gray-100/50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3.5 text-red-600 hover:bg-red-50 hover:text-red-700 font-medium rounded-2xl transition-all group">
                <div class="w-8 h-8 rounded-full bg-red-50 text-red-400 flex items-center justify-center mr-3 group-hover:bg-red-100 group-hover:text-red-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </div>
                Çıkış Yap
            </button>
        </form>
    </div>
</nav>
