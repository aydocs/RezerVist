@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('vendor.business.edit') }}" class="text-primary hover:text-purple-700 font-medium inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                İşletme Ayarlarına Dön
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Çalışma Saatleri</h1>
            <p class="text-gray-500 mb-8">İşletmenizin haftalık çalışma saatlerini ve özel günleri yönetin.</p>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('vendor.business.hours.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Weekly Hours -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Haftalık Çalışma Saatleri</h3>
                        <div class="space-y-4">
                            @php
                                $days = [
                                    1 => 'Pazartesi',
                                    2 => 'Salı',
                                    3 => 'Çarşamba',
                                    4 => 'Perşembe',
                                    5 => 'Cuma',
                                    6 => 'Cumartesi',
                                    0 => 'Pazar'
                                ];
                                $existingHours = $hours->keyBy('day_of_week');
                            @endphp

                            @foreach($days as $dayNum => $dayName)
                                @php
                                    $hour = $existingHours->get($dayNum);
                                @endphp
                                <div class="flex flex-wrap items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                    <div class="w-32">
                                        <label class="font-semibold text-gray-900">{{ $dayName }}</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" 
                                               id="closed_{{ $dayNum }}" 
                                               name="hours[{{ $dayNum }}][is_closed]"
                                               value="1"
                                               {{ $hour && $hour->is_closed ? 'checked' : '' }}
                                               onchange="toggleDayInputs({{ $dayNum }})"
                                               class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                        <label for="closed_{{ $dayNum }}" class="text-sm text-gray-600">Kapalı</label>
                                    </div>
                                    <div class="flex items-center gap-4 day-inputs-{{ $dayNum }}" style="{{ $hour && $hour->is_closed ? 'display: none;' : '' }}">
                                        <div class="flex items-center gap-2">
                                            <input type="time" 
                                                name="hours[{{ $dayNum }}][open_time]" 
                                                value="{{ $hour ? \Carbon\Carbon::parse($hour->open_time)->format('H:i') : '09:00' }}"
                                                class="px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20">
                                            <span class="text-gray-500">-</span>
                                            <input type="time" 
                                                name="hours[{{ $dayNum }}][close_time]" 
                                                value="{{ $hour ? \Carbon\Carbon::parse($hour->close_time)->format('H:i') : '18:00' }}"
                                                class="px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20">
                                        </div>
                                        
                                        <div class="h-8 w-px bg-gray-200 mx-2"></div>
                                        
                                        <div class="flex items-center gap-2">
                                            <div class="relative">
                                                <input type="number" 
                                                       name="hours[{{ $dayNum }}][discount_percent]" 
                                                       value="{{ $hour->discount_percent ?? '' }}"
                                                       placeholder="0"
                                                       min="0" max="100"
                                                       class="w-20 pl-4 pr-8 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20">
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">%</span>
                                            </div>
                                            <label class="text-[10px] font-black text-primary uppercase tracking-widest">Happy Hour</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Special Days (Coming Soon) -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Özel Günler / Tatiller</h3>
                        <p class="text-sm text-gray-500 mb-4">Yılbaşı, bayramlar veya özel etkinlikler için farklı çalışma saatleri belirleyin.</p>
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition gap-2 opacity-50 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Özel Gün Ekle (Yakında)
                        </a>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-purple-700 transition shadow-md hover:shadow-lg">
                            Kaydet
                        </button>
                        <a href="{{ route('vendor.business.edit') }}" class="px-8 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">
                            İptal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleDayInputs(dayNum) {
    const checkbox = document.getElementById(`closed_${dayNum}`);
    const inputs = document.querySelector(`.day-inputs-${dayNum}`);
    if (checkbox.checked) {
        inputs.style.display = 'none';
    } else {
        inputs.style.display = 'flex';
    }
}
</script>
@endsection
