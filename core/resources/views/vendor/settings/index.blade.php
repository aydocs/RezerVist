@extends('layouts.app')

@section('title', 'İşletme Ayarları - ' . $business->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">İşletme Ayarları</h1>
            <p class="text-slate-500 mt-2">İşletmenizin çalışma şeklini ve müşteri özelliklerini buradan kontrol edebilirsiniz.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('vendor.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Genel Durum / Acil Mod -->
                <div class="bg-gradient-to-r from-rose-500 to-red-600 rounded-2xl shadow-lg border border-red-400 overflow-hidden text-white">
                    <div class="p-6 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4 backdrop-blur-sm">
                                <i class="fas fa-bolt text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">Yoğunluk Modu (Busy Mode)</h2>
                                <p class="text-white/80 text-sm">Açık olduğunda arama sonuçlarında "Şu an Yoğunuz" ibaresi görünür.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="busy_mode" value="1" class="sr-only peer" {{ $business->isBusyMode() ? 'checked' : '' }}>
                            <div class="w-14 h-7 bg-white/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-white/40"></div>
                        </label>
                    </div>
                </div>

                <!-- Rezervasyon Ayarları -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                        <h2 class="text-xl font-semibold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-check mr-3 text-indigo-500"></i>
                            Rezervasyon Kuralları
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Global Toggle -->
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div>
                                <h3 class="font-bold text-slate-800">Online Rezervasyonları Kabul Et</h3>
                                <p class="text-sm text-slate-500 mt-1">Kapalıysa müşteriler yeni rezervasyon yapamaz.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="reservations_enabled" value="1" class="sr-only peer" {{ $business->isReservationsEnabled() ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                            <!-- Auto Confirm -->
                            <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-slate-100 shadow-sm">
                                <div>
                                    <h3 class="font-medium text-slate-800">Otomatik Onay (Auto-Confirm)</h3>
                                    <p class="text-xs text-slate-500 mt-1">Onay sürecini atla, doğrudan sisteme işle.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="auto_confirm" value="1" class="sr-only peer" {{ $business->isAutoConfirmEnabled() ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                            </div>

                            <!-- Loyalty -->
                            <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-slate-100 shadow-sm">
                                <div>
                                    <h3 class="font-medium text-slate-800">Puan Sistemi (Loyalty)</h3>
                                    <p class="text-xs text-slate-500 mt-1">Müşteriler puan kazanıp harcayabilsin.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="loyalty_enabled" value="1" class="sr-only peer" {{ $business->isLoyaltyEnabled() ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Numerical Windows -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Min. Ön Hazırlık (Saat)</label>
                                <input type="number" name="min_advance_hours" value="{{ $business->getMinAdvanceBookingTime() }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition" min="0" placeholder="Örn: 2">
                                <p class="text-[10px] text-slate-400 mt-1">En erken kaç saat önce rezervasyon yapılabilir?</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Max. İleri Tarih (Gün)</label>
                                <input type="number" name="max_ahead_days" value="{{ $business->getMaxBookingAheadDays() }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition" min="1" placeholder="Örn: 30">
                                <p class="text-[10px] text-slate-400 mt-1">En fazla kaç gün sonrasına tarih seçilebilir?</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">İptal Limiti (Saat)</label>
                                <input type="number" name="cancellation_limit" value="{{ $business->getCancellationWindow() }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition" min="0" placeholder="Örn: 24">
                                <p class="text-[10px] text-slate-400 mt-1">Rezervasyona kaç saat kala iptal edilebilir?</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ödeme ve QR Ayarları -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                        <h2 class="text-xl font-semibold text-slate-800 flex items-center">
                            <i class="fas fa-qrcode mr-3 text-indigo-500"></i>
                            Ödeme ve QR Sistemleri
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <div>
                                    <h3 class="font-medium text-slate-800">QR Menüden Ödeme</h3>
                                    <p class="text-sm text-slate-500 mt-1">Müşteriler masadan hesap ödeyebilir.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="qr_payments_enabled" value="1" class="sr-only peer" {{ $business->isQrPaymentsEnabled() ? 'checked' : '' }}>
                                    <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <div>
                                    <h3 class="font-medium text-slate-800">Garson Çağır Butonu</h3>
                                    <p class="text-sm text-slate-500 mt-1">QR menüde garson çağır aktiftir.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="qr_waiter_call" value="1" class="sr-only peer" {{ $business->isWaiterCallEnabled() ? 'checked' : '' }}>
                                    <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Masa Yönetimi -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                        <h2 class="text-xl font-semibold text-slate-800 flex items-center">
                            <i class="fas fa-chair mr-3 text-indigo-500"></i>
                            Masa Bazlı Rezervasyon Kontrolü
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($resources->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($resources as $resource)
                                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-white rounded-lg border border-slate-100 flex items-center justify-center mr-3 shadow-sm text-slate-400">
                                                <i class="fas fa-couch text-sm"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-medium text-slate-800">{{ $resource->name }}</h3>
                                                <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tight">Kapasite: {{ $resource->capacity }} Kişi</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="resource_reservations[{{ $resource->id }}]" value="1" class="sr-only peer" {{ $resource->is_reservation_enabled ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-slate-400 mt-4 italic text-center">
                                * Sadece aktif (kullanılabilir) olan masalar listelenmektedir.
                            </p>
                        @else
                            <div class="text-center py-10">
                                <div class="mb-4">
                                    <i class="fas fa-info-circle text-4xl text-slate-200"></i>
                                </div>
                                <h3 class="font-medium text-slate-800">Masa Bulunamadı</h3>
                                <p class="text-sm text-slate-500 mt-2">Henüz bir masa tanımlamamışsınız.</p>
                                <a href="{{ route('vendor.resources.index') }}" class="mt-4 inline-block text-indigo-600 text-sm font-bold hover:underline">
                                    Masa Eklemek İçin Tıklayın
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center transform hover:scale-[1.02]">
                    <i class="fas fa-save mr-2"></i>
                    Ayarları Kaydet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
