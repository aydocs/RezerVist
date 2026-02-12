@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Refined Premium Header -->
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div class="space-y-4">
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Panel</a>
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    <span class="text-slate-300">Kullanıcılar</span>
                </nav>
                <div>
                    <h1 class="text-5xl font-black text-slate-900 tracking-tight mb-4">
                        Kullanıcı Yönetimi
                    </h1>
                    <div class="flex items-center gap-4">
                        <span class="w-12 h-1 bg-purple-600 rounded-full"></span>
                        <p class="text-slate-500 font-medium text-lg">Platformdaki tüm kullanıcıları görüntüleyin ve yönetin</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-5 px-6 py-3.5 bg-white hover:bg-slate-50 text-slate-700 rounded-full transition-all duration-300 shadow-sm border border-slate-100 font-black text-[11px] uppercase tracking-widest active:scale-95">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-purple-600 transition-all duration-300">
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    <span>Geri Dön</span>
                </a>
            </div>
        </div>

        <!-- Refined Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Toplam Kullanıcı (Blue) -->
            <div class="group relative bg-white p-6 rounded-[2rem] shadow-sm border border-blue-100/50 transition-all duration-300 hover:scale-[1.02] overflow-hidden">
                <div class="relative flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/20 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-1">Toplam Kullanıcı</p>
                        <p class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($totalUsers) }}</p>
                    </div>
                </div>
            </div>

            <!-- İşletme Sahibi (Purple) -->
            <div class="group relative bg-white p-6 rounded-[2rem] shadow-sm border border-purple-100/50 transition-all duration-300 hover:scale-[1.02] overflow-hidden">
                <div class="relative flex items-center gap-5">
                    <div class="w-14 h-14 bg-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-600/20 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-purple-400 uppercase tracking-[0.2em] mb-1">İşletme Sahibi</p>
                        <p class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($businessUsersCount) }}</p>
                    </div>
                </div>
            </div>

            <!-- Müşteri (Green) -->
            <div class="group relative bg-white p-7 rounded-[2.5rem] shadow-xl shadow-emerald-200/40 border border-emerald-100/50 transition-all duration-500 hover:scale-[1.02] overflow-hidden">
                <div class="relative flex items-center gap-6">
                    <div class="w-16 h-16 bg-emerald-500 rounded-3xl flex items-center justify-center shadow-2xl shadow-emerald-500/40 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-emerald-600/70 uppercase tracking-[0.2em] mb-1">Müşteri</p>
                        <p class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($customerUsersCount) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6" 
             x-data="{ 
                showRoleDropdown: false, 
                selectedRole: '{{ request('role') }}',
                roleText: '{{ request('role') == 'business' ? 'İşletme' : (request('role') == 'customer' ? 'Müşteri' : 'Tüm Roller') }}',
                showDatePicker: false,
                selectedDate: '{{ request('date_from') }}',
                searchQuery: '{{ request('search') }}',
                searchResults: [],
                showSearchDropdown: false,
                currentMonth: new Date().getMonth(),
                currentYear: new Date().getFullYear(),
                selectedDay: null,
                
                async searchUsers() {
                    if (this.searchQuery.length < 1) {
                        this.searchResults = [];
                        this.showSearchDropdown = false;
                        return;
                    }
                    
                    try {
                        let url = `/admin/users/search?q=${encodeURIComponent(this.searchQuery)}`;
                        if (this.selectedRole) {
                            url += `&role=${encodeURIComponent(this.selectedRole)}`;
                        }
                        const response = await fetch(url);
                        const data = await response.json();
                        this.searchResults = data;
                        this.showSearchDropdown = data.length > 0;
                    } catch (error) {
                        console.error('Search error:', error);
                    }
                },
                
                getDaysInMonth() {
                    return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
                },
                
                getFirstDayOfMonth() {
                    return new Date(this.currentYear, this.currentMonth, 1).getDay();
                },
                
                prevMonth() {
                    if (this.currentMonth === 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                },
                
                nextMonth() {
                    if (this.currentMonth === 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                },
                
                selectDate(day) {
                    this.selectedDay = day;
                    const month = String(this.currentMonth + 1).padStart(2, '0');
                    const dayStr = String(day).padStart(2, '0');
                    this.selectedDate = `${this.currentYear}-${month}-${dayStr}`;
                    this.showDatePicker = false;
                },
                
                getMonthName() {
                    const months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 
                                   'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
                    return months[this.currentMonth] + ' ' + this.currentYear;
                }
             }">
            <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search with Autocomplete -->
                    <div class="md:col-span-2 relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ara</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input 
                                type="text" 
                                name="search" 
                                x-model="searchQuery"
                                @input.debounce.300ms="searchUsers()"
                                @focus="if(searchResults.length > 0) showSearchDropdown = true"
                                placeholder="İsim veya e-posta..." 
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all shadow-sm"
                                autocomplete="off">
                        </div>
                        
                        <!-- Search Dropdown -->
                        <div x-show="showSearchDropdown" 
                             @click.away="showSearchDropdown = false"
                             x-transition
                             class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-2xl border border-gray-200 max-h-96 overflow-y-auto"
                             style="display: none;">
                            <template x-for="user in searchResults" :key="user.id">
                                <a :href="`/admin/users/${user.id}/edit`" 
                                   class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50 transition-colors border-b border-gray-100 last:border-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold">
                                        <span x-text="user.name.charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900" x-text="user.name"></p>
                                        <p class="text-xs text-gray-500" x-text="user.email"></p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-bold rounded-full" 
                                          :class="user.role === 'business' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'"
                                          x-text="user.role === 'business' ? 'İşletme' : 'Müşteri'"></span>
                                </a>
                            </template>
                        </div>
                    </div>

                    <!-- Custom Role Dropdown -->
                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Rol</label>
                        <input type="hidden" name="role" x-model="selectedRole">
                        <button type="button" @click="showRoleDropdown = !showRoleDropdown" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all shadow-sm bg-white text-left flex items-center justify-between">
                            <span x-text="roleText" class="text-gray-900"></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{'rotate-180': showRoleDropdown}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="showRoleDropdown" @click.away="showRoleDropdown = false" x-transition class="absolute z-10 w-full mt-2 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" style="display: none;">
                            <button type="button" @click="selectedRole = ''; roleText = 'Tüm Roller'; showRoleDropdown = false" class="w-full px-4 py-3 text-left hover:bg-purple-50 transition-colors" :class="{'bg-purple-100 font-semibold': selectedRole === ''}">
                                Tüm Roller
                            </button>
                            <button type="button" @click="selectedRole = 'business'; roleText = 'İşletme'; showRoleDropdown = false" class="w-full px-4 py-3 text-left hover:bg-purple-50 transition-colors border-t border-gray-100" :class="{'bg-purple-100 font-semibold': selectedRole === 'business'}">
                                İşletme Sahibi
                            </button>
                            <button type="button" @click="selectedRole = 'customer'; roleText = 'Müşteri'; showRoleDropdown = false" class="w-full px-4 py-3 text-left hover:bg-purple-50 transition-colors border-t border-gray-100" :class="{'bg-purple-100 font-semibold': selectedRole === 'customer'}">
                                Müşteri
                            </button>
                        </div>
                    </div>

                    <!-- Modern Calendar -->
                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kayıt Tarihi</label>
                        <input type="hidden" name="date_from" x-model="selectedDate">
                        <button type="button" @click="showDatePicker = !showDatePicker" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all shadow-sm bg-white text-left flex items-center justify-between">
                            <span x-text="selectedDate || 'Tarih Seçin'" class="text-gray-900" :class="{'text-gray-400': !selectedDate}"></span>
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </button>
                        
                        <!-- Calendar Widget -->
                        <div x-show="showDatePicker" @click.away="showDatePicker = false" x-transition class="absolute z-10 mt-2 bg-white rounded-2xl shadow-2xl border border-gray-200 p-5" style="display: none; min-width: 320px;">
                            <!-- Month Navigation -->
                            <div class="flex items-center justify-between mb-4">
                                <button type="button" @click="prevMonth()" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <h3 class="text-lg font-bold text-gray-900" x-text="getMonthName()"></h3>
                                <button type="button" @click="nextMonth()" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>
                            
                            <!-- Day Names -->
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <template x-for="day in ['Pt', 'Sa', 'Ça', 'Pe', 'Cu', 'Ct', 'Pz']">
                                    <div class="text-center text-xs font-semibold text-gray-500 py-2" x-text="day"></div>
                                </template>
                            </div>
                            
                            <!-- Calendar Days -->
                            <div class="grid grid-cols-7 gap-1">
                                <template x-for="blank in getFirstDayOfMonth()">
                                    <div class="aspect-square"></div>
                                </template>
                                <template x-for="day in getDaysInMonth()">
                                    <button 
                                        type="button"
                                        @click="selectDate(day)"
                                        class="aspect-square flex items-center justify-center rounded-lg text-sm font-medium transition-all"
                                        :class="selectedDay === day ? 'bg-purple-600 text-white' : 'hover:bg-purple-50 text-gray-700'"
                                        x-text="day">
                                    </button>
                                </template>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex gap-2 mt-4 pt-4 border-t border-gray-200">
                                <button type="button" @click="selectedDate = ''; selectedDay = null; showDatePicker = false" class="flex-1 px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                    Temizle
                                </button>
                                <button type="button" @click="showDatePicker = false" class="flex-1 px-4 py-2 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                                    Tamam
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:shadow-lg hover:shadow-purple-500/50 transition-all font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filtrele
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Temizle
                    </a>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kullanıcı</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">İletişim</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">İstatistikler</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kayıt</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold shadow-lg shadow-purple-500/30">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                        @if($user->ownedBusiness)
                                            <p class="text-xs text-purple-600">{{ $user->ownedBusiness->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $user->email }}</p>
                                <p class="text-xs text-gray-500">{{ $user->phone ?? 'Telefon yok' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full {{ $user->role === 'business' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $user->role === 'business' ? 'İşletme' : 'Müşteri' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    @if($user->reservations_count > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700">
                                            {{ $user->reservations_count }} Rezervasyon
                                        </span>
                                    @endif
                                    @if($user->businesses_count > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700">
                                            {{ $user->businesses_count }} İşletme
                                        </span>
                                    @endif
                                    @if($user->reservations_count == 0 && $user->businesses_count == 0)
                                        <span class="text-xs text-gray-400">Aktivite yok</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <p>{{ $user->created_at->format('d.m.Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Düzenle">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <button onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Sil">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Kullanıcı bulunamadı</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif

    </div>
</div>

<script>
function confirmDelete(userId, userName) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: userName + " kullanıcısını silmek istediğinize emin misiniz?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Evet, Sil',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + userId).submit();
        }
    });
}
</script>
@endsection
