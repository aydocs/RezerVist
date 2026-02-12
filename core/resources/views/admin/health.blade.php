@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary transition-colors">
                        Yönetim Paneli
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Sistem Sağlığı</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Sistem Sağlık İzleme</h1>
                <p class="mt-2 text-sm text-gray-600">Sunucu, veritabanı ve uygulama metriklerini gerçek zamanlı izleyin.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.location.reload()" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Yenile
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Database Health -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 transition-all hover:scale-[1.02] duration-300">
                <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-blue-500 to-blue-600">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-black text-white">Veritabanı</h2>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">{{ $health['database']['status'] }}</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Veritabanı Adı</span>
                        <span class="font-black text-gray-900">{{ $health['database']['name'] ?? 'Bilinmiyor' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Boyut</span>
                        <span class="font-black text-gray-900">{{ $health['database']['size'] ?? '0 MB' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Driver</span>
                        <span class="font-black text-gray-900 uppercase">{{ $health['database']['connection'] ?? 'mysql' }}</span>
                    </div>
                </div>
            </div>

            <!-- Server Health -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 transition-all hover:scale-[1.02] duration-300">
                <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-indigo-500 to-indigo-600">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-black text-white">Sunucu</h2>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">ÇALIŞIYOR</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">PHP Sürümü</span>
                        <span class="font-black text-gray-900">{{ $health['server']['php_version'] }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Laravel Sürümü</span>
                        <span class="font-black text-gray-900">{{ $health['server']['laravel_version'] }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Memory Limit</span>
                        <span class="font-black text-gray-900 uppercase">{{ $health['server']['memory_limit'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Storage Health -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 transition-all hover:scale-[1.02] duration-300">
                <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-emerald-500 to-emerald-600">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-black text-white">Depolama</h2>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">DURUM: OK</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Boş Alan</span>
                        <span class="font-black text-gray-900">{{ $health['storage']['disk_free'] }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Toplam Alan</span>
                        <span class="font-black text-gray-900">{{ $health['storage']['disk_total'] }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Yazma İzni</span>
                        <span class="font-black text-emerald-600">{{ $health['storage']['is_writable'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Cache & Queue -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 lg:col-span-2 transition-all hover:scale-[1.01] duration-300">
                <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-slate-800 to-slate-900">
                    <h2 class="text-lg font-black text-white">Performans ve Kuyruk</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 divide-x divide-gray-100">
                    <div class="p-8 space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Cache Driver</p>
                                <p class="text-xl font-black text-gray-900 uppercase">{{ $health['cache']['driver'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Cache Durumu</p>
                                <p class="text-xl font-black text-gray-900">{{ $health['cache']['status'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kuyruk Driver</p>
                                <p class="text-xl font-black text-gray-900 uppercase">{{ $health['queue']['driver'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Hatalı İşler</p>
                                <p class="text-xl font-black {{ $health['queue']['failed_jobs'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $health['queue']['failed_jobs'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Environment -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 transition-all hover:scale-[1.02] duration-300">
                <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-orange-500 to-orange-600">
                    <h2 class="text-lg font-black text-white">Ortam</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Environment</span>
                        <span class="px-2 py-0.5 bg-orange-100 text-orange-700 rounded-lg font-black uppercase text-[10px]">{{ $health['environment']['env'] }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Debug Mode</span>
                        <span class="font-black {{ $health['environment']['debug'] == 'Enabled' ? 'text-red-500' : 'text-gray-900' }}">{{ $health['environment']['debug'] }}</span>
                    </div>
                    <div class="text-sm">
                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px] block mb-1">APP_URL</span>
                        <span class="font-black text-gray-900 text-xs">{{ $health['environment']['url'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
