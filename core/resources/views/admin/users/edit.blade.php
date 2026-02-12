@extends('layouts.app')

@section('title', 'Kullanıcı Düzenle')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mb-2">
                    Kullanıcı Düzenle
                </h1>
                <p class="text-gray-600">{{ $user->name }} - Kullanıcı bilgilerini güncelleyin</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-white rounded-xl transition-all border border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="font-medium">Geri Dön</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <!-- Profile Header -->
            <div class="h-24 bg-gradient-to-r from-purple-600 to-purple-800 relative rounded-t-2xl"></div>
            <div class="px-8 pb-8 -mt-12">
                <div class="relative inline-block mb-6">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white text-4xl font-bold border-4 border-white shadow-xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="absolute bottom-0 right-0 w-7 h-7 rounded-full {{ $user->role === 'business' ? 'bg-purple-500' : 'bg-gray-500' }} border-4 border-white flex items-center justify-center">
                        @if($user->role === 'business')
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        @else
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        @endif
                    </span>
                </div>

                <form id="edit-user-form" action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Name -->
                        <div class="relative">
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="block px-4 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-300 appearance-none focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent peer" placeholder=" " required />
                            <label for="name" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:text-purple-600">Ad Soyad</label>
                        </div>
                        
                        <!-- Email -->
                        <div class="relative">
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="block px-4 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-300 appearance-none focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent peer" placeholder=" " required />
                            <label for="email" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:text-purple-600">E-posta</label>
                        </div>

                        <!-- Phone -->
                        <div class="relative">
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="block px-4 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-300 appearance-none focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent peer" placeholder=" " />
                            <label for="phone" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:text-purple-600">Telefon</label>
                        </div>

                        <!-- Role Custom Dropdown -->
                        <div class="relative" x-data="{ 
                            open: false, 
                            selected: '{{ old('role', $user->role) }}',
                            roles: {
                                'customer': { label: 'Müşteri', icon: '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\'></path></svg>', color: 'text-gray-600', bg: 'bg-gray-50' },
                                'business': { label: 'İşletme Sahibi', icon: '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\'></path></svg>', color: 'text-gray-600', bg: 'bg-gray-50' },
                                'admin': { label: 'Yönetici', icon: '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z\'></path></svg>', color: 'text-gray-600', bg: 'bg-gray-50' }
                            }
                        }">
                            <input type="hidden" name="role" x-model="selected">
                            
                            <button type="button" 
                                    @click="open = !open" 
                                    class="relative w-full bg-gray-50 border border-gray-300 rounded-xl px-4 pb-2.5 pt-4 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                    :class="{ 'ring-2 ring-purple-500 border-transparent': open }">
                                <span class="flex items-center gap-3">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-lg bg-white shadow-sm" 
                                          :class="roles[selected].color"
                                          x-html="roles[selected].icon">
                                    </span>
                                    <span class="block truncate font-medium text-gray-900 text-sm" x-text="roles[selected].label"></span>
                                </span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 transition-transform duration-200" :class="{ 'transform rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>
                            
                            <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4">Kullanıcı Rolü</label>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 mt-2 w-full bg-white shadow-2xl rounded-xl border border-gray-100 py-1 overflow-hidden focus:outline-none ring-1 ring-black ring-opacity-5">
                                
                                <template x-for="(data, role) in roles" :key="role">
                                    <div @click="selected = role; open = false"
                                         class="cursor-pointer select-none relative py-3 pl-3 pr-9 hover:bg-purple-50 transition-colors border-b border-gray-50 last:border-0">
                                        <div class="flex items-center gap-3">
                                            <span class="flex items-center justify-center w-8 h-8 rounded-lg" 
                                                  :class="data.bg + ' ' + data.color"
                                                  x-html="data.icon">
                                            </span>
                                            <span class="font-medium block truncate" 
                                                  :class="{ 'text-purple-700': selected === role, 'text-gray-900': selected !== role }"
                                                  x-text="data.label">
                                            </span>
                                        </div>

                                        <span x-show="selected === role" class="absolute inset-y-0 right-0 flex items-center pr-4 text-purple-600">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="border-t border-gray-100 pt-6 mb-6">
                        <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Şifre Değiştir (İsteğe Bağlı)
                        </h4>
                        <div class="relative">
                            <input type="password" id="password" name="password" class="block px-4 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-300 appearance-none focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent peer" placeholder=" " />
                            <label for="password" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:text-purple-600">Yeni Şifre (Boş bırakılabilir)</label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold">
                            İptal
                        </a>
                        <button type="button" onclick="confirmUpdate()" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:shadow-lg hover:shadow-purple-500/50 transition-all font-bold">
                            Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmUpdate() {
    Swal.fire({
        title: 'Değişiklikleri Kaydet?',
        text: "Kullanıcı bilgileri güncellenecek!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#7C3AED',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Evet, Güncelle',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('edit-user-form').submit();
        }
    });
}
</script>
@endsection
