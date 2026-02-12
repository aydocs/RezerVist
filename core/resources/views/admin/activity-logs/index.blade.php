@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mb-2">
                    Aktivite Logları
                </h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Tüm sistem aktivitelerini ve güvenlik olaylarını görüntüleyin
                </p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 px-5 py-2.5 text-gray-600 hover:text-white bg-white hover:bg-gradient-to-r hover:from-purple-600 hover:to-purple-700 rounded-xl transition-all shadow-sm hover:shadow-lg border border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="font-semibold">Panele Dön</span>
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Toplam Log</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalLogs) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-700 rounded-xl flex items-center justify-center shadow-lg shadow-gray-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Başarılı Giriş</p>
                        <p class="text-3xl font-bold text-green-600">{{ number_format($loginAttempts) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-700 rounded-xl flex items-center justify-center shadow-lg shadow-green-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Başarısız Giriş</p>
                        <p class="text-3xl font-bold text-red-600">{{ number_format($failedLogins) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Çıkış</p>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($logouts) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="space-y-6">
                
                <!-- Category Tabs -->
                <div class="flex flex-wrap gap-2 border-b border-gray-100 pb-4">
                    <a href="{{ route('admin.activity-logs.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('category') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Tümü
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['category' => 'auth']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('category') == 'auth' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Kimlik Doğrulama
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['category' => 'payment']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('category') == 'payment' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Ödemeler
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['category' => 'reservation']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('category') == 'reservation' ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Rezervasyonlar
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['category' => 'business']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('category') == 'business' ? 'bg-amber-100 text-amber-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        İşletme İşlemleri
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['category' => 'system']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('category') == 'system' ? 'bg-gray-100 text-gray-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        Sistem
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Detaylı İşlem Tipi</label>
                        <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Tümü</option>
                            <optgroup label="Ödemeler">
                                <option value="payment_success" {{ request('type') == 'payment_success' ? 'selected' : '' }}>Başarılı Ödeme</option>
                                <option value="payment_failed" {{ request('type') == 'payment_failed' ? 'selected' : '' }}>Başarısız Ödeme</option>
                                <option value="payment_refunded" {{ request('type') == 'payment_refunded' ? 'selected' : '' }}>İade</option>
                            </optgroup>
                            <optgroup label="Rezervasyonlar">
                                <option value="reservation_created" {{ request('type') == 'reservation_created' ? 'selected' : '' }}>Oluşturuldu</option>
                                <option value="reservation_cancelled" {{ request('type') == 'reservation_cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                                <option value="reservation_completed" {{ request('type') == 'reservation_completed' ? 'selected' : '' }}>Tamamlandı</option>
                            </optgroup>
                            <optgroup label="Kimlik">
                                <option value="login" {{ request('type') == 'login' ? 'selected' : '' }}>Giriş</option>
                                <option value="logout" {{ request('type') == 'logout' ? 'selected' : '' }}>Çıkış</option>
                                <option value="failed_login" {{ request('type') == 'failed_login' ? 'selected' : '' }}>Hatalı Giriş</option>
                                <option value="user_created" {{ request('type') == 'user_created' ? 'selected' : '' }}>Yeni Üye</option>
                            </optgroup>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tarih</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">IP veya Kullanıcı</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="IP, İsim veya Email..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:shadow-lg hover:shadow-purple-500/50 transition-all font-semibold">
                            Filtrele
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tarih</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kullanıcı</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Aktivite</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">IP Adresi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Detaylar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $log->created_at->format('d.m.Y H:i:s') }}</div>
                                <div class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->user)
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Bilinmeyen</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full 
                                        {{ $log->action_type == 'login' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $log->action_type == 'logout' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $log->action_type == 'failed_login' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ !in_array($log->action_type, ['login', 'logout', 'failed_login']) ? 'bg-gray-100 text-gray-800' : '' }}
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $log->action_type)) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $log->description }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="px-2 py-1 bg-gray-100 rounded text-xs font-mono">{{ $log->ip_address ?? 'N/A' }}</code>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->user_agent)
                                    <p class="text-xs text-gray-500 truncate max-w-xs" title="{{ $log->user_agent }}">{{ $log->user_agent }}</p>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Henüz log kaydı bulunmamaktadır
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
