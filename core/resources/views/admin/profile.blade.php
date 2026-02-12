@extends('layouts.app')

@section('title', 'Yönetici Profili - ' . $globalSettings['site_name'])

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mb-2">
                    Yönetici Profili
                </h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Kişisel bilgilerinizi ve hesap ayarlarınızı yönetin
                </p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 px-5 py-2.5 text-gray-600 hover:text-white bg-white hover:bg-gradient-to-r hover:from-purple-600 hover:to-purple-700 rounded-xl transition-all shadow-sm hover:shadow-lg border border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="font-semibold">Panele Dön</span>
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar / Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-28">
                    <div class="h-24 bg-gradient-to-r from-primary to-purple-800 relative"></div>
                    <div class="px-6 pb-6 text-center -mt-12 relative">
                        <div class="relative inline-block mb-4 group">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md mx-auto">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center text-white text-3xl font-bold border-4 border-white shadow-md mx-auto">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="absolute bottom-0 right-0">
                                @csrf
                                <label for="photo" class="bg-primary text-white p-2 rounded-full cursor-pointer hover:bg-primary-dark transition shadow-lg block transform hover:scale-110">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </label>
                                <input type="file" name="photo" id="photo" class="hidden" onchange="this.form.submit()">
                            </form>
                        </div>
                        
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 mt-2">
                            <span class="w-2 h-2 rounded-full bg-purple-600 mr-2"></span>
                            Sistem Yöneticisi
                        </span>
                        
                        <div class="mt-6 border-t border-gray-100 pt-6 text-left">
                            <div class="flex items-center text-gray-600 text-sm mb-3">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $user->email }}
                            </div>
                            <div class="flex items-center text-gray-600 text-sm">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Üyelik: {{ $user->created_at->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-900">Hesap Bilgileri</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <!-- Name -->
                                <div class="relative">
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="block px-4 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer" placeholder=" " />
                                    <label for="name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:text-primary">Ad Soyad</label>
                                </div>
                                
                                <!-- Email (Read Only) -->
                                <div class="relative">
                                    <input type="email" value="{{ $user->email }}" disabled class="block px-4 pb-2.5 pt-5 w-full text-sm text-gray-500 bg-gray-100 rounded-lg border-1 border-gray-200 appearance-none cursor-not-allowed peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4">E-posta Adresi</label>
                                </div>

                                <!-- Phone -->
                                <div class="relative">
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="block px-4 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer" placeholder=" " />
                                    <label for="phone" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:text-primary">Telefon Numarası</label>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-100 pt-8 mb-6">
                                <h4 class="text-sm font-bold text-gray-900 mb-6 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Şifre Değiştir
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="relative">
                                        <input type="password" id="current_password" name="current_password" class="block px-4 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer" placeholder=" " />
                                        <label for="current_password" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:text-primary">Mevcut Şifre</label>
                                    </div>
                                    <div class="relative">
                                        <input type="password" id="password" name="password" class="block px-4 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer" placeholder=" " />
                                        <label for="password" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:text-primary">Yeni Şifre</label>
                                    </div>
                                    <div class="relative">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="block px-4 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer" placeholder=" " />
                                        <label for="password_confirmation" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:text-primary">Yeni Şifre (Tekrar)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="button" onclick="confirmSaveProfile()" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-purple-600 to-purple-700 hover:shadow-lg hover:shadow-purple-500/50 transition-all">
                                    Değişiklikleri Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmSaveProfile() {
    Swal.fire({
        title: 'Değişiklikleri Kaydet?',
        text: "Profil bilgileriniz güncellenecek!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#7C3AED',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Evet, Kaydet',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.querySelector('form').submit();
        }
    });
}
</script>
@endsection
