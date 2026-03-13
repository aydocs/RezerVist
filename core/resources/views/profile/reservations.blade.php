@extends('layouts.app')

@section('title', 'Rezervasyonlarım - ' . ($globalSettings['site_name'] ?? config('app.name')))

@section('content')
@php
    $user = auth()->user();
@endphp

<div class="bg-gray-50/50 min-h-screen py-12 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ photoPreview: '{{ $user->profile_photo_url }}', editingPhoto: false }">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3">
                <div class="sticky top-24">
                    @include('profile.partials.sidebar')
                </div>
            </div>

            <!-- Content -->
            <div class="lg:col-span-9">
                 <div class="mb-8 flex items-end justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Rezervasyonlarım</h1>
                        <p class="text-gray-500 mt-2">Geçmiş ve gelecek tüm rezervasyonlarınızı buradan yönetebilirsiniz.</p>
                    </div>
                </div>

                @if($reservations->isEmpty())
                    <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-xl shadow-gray-100/50">
                        <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Henüz rezervasyonunuz yok</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">Harika mekanları keşfedip ilk rezervasyonunuzu oluşturmaya ne dersiniz?</p>
                        <a href="/search" class="inline-flex items-center px-8 py-4 bg-primary text-white rounded-2xl font-black border-2 border-white/20 hover:border-white shadow-lg shadow-primary/30 transition hover:scale-105">
                            İşletmeleri Keşfet
                        </a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($reservations as $reservation)
                        <div class="bg-white rounded-3xl p-5 md:p-6 border border-gray-100 shadow-lg shadow-gray-100/50 hover:shadow-xl hover:border-primary/20 transition-all duration-300 group">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 flex-1">
                                    <div class="shrink-0">
                                        <div class="w-20 h-20 bg-gradient-to-br from-primary to-purple-700 rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-primary/20 text-white">
                                            {{ substr($reservation->business->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0 text-center sm:text-left py-1">
                                        <h4 class="font-bold text-gray-900 text-xl mb-3 group-hover:text-primary transition-colors line-clamp-1">{{ $reservation->business->name }}</h4>
                                        <div class="flex flex-wrap justify-center sm:justify-start gap-2 md:gap-4 text-sm text-gray-600">
                                            <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                                <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="font-semibold">{{ $reservation->start_time->format('d M Y') }}</span>
                                            </div>
                                            <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                                <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span class="font-semibold">{{ $reservation->start_time->format('H:i') }}</span>
                                            </div>
                                            <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                                <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                <span class="font-semibold">{{ $reservation->guest_count }} Kişi</span>
                                            </div>
                                        </div>
                                        @if($reservation->note)
                                            <div class="mt-3 flex items-start gap-2 text-sm text-gray-500 bg-gray-50 p-3 rounded-xl border border-gray-100 text-left">
                                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                                <span class="italic line-clamp-2 md:line-clamp-none">{{ $reservation->note }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-start gap-3 min-w-[140px]">
                                    <span class="px-4 py-2 rounded-xl text-[10px] md:text-sm font-bold uppercase tracking-wider
                                        @if($reservation->status == 'approved' || $reservation->status == 'confirmed') bg-green-100 text-green-700 border border-green-200
                                        @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-700 border border-yellow-200
                                        @elseif($reservation->status == 'pending_payment') bg-orange-100 text-orange-700 border border-orange-200
                                        @elseif($reservation->status == 'cancelled') bg-red-100 text-red-700 border border-red-200
                                        @elseif($reservation->status == 'completed') bg-blue-100 text-blue-700 border border-blue-200
                                        @else bg-gray-100 text-gray-700 border border-gray-200 @endif">
                                        @if($reservation->status == 'pending_payment')
                                            Ödeme Bekliyor
                                        @elseif($reservation->status == 'pending')
                                            Onay Bekliyor
                                        @elseif($reservation->status == 'approved' || $reservation->status == 'confirmed')
                                            Onaylandı
                                        @elseif($reservation->status == 'cancelled')
                                            İptal Edildi
                                        @elseif($reservation->status == 'completed')
                                            Tamamlandı
                                        @else
                                            {{ ucfirst($reservation->status) }}
                                        @endif
                                    </span>
                                    <span class="text-xl font-black text-gray-900 tabular-nums">₺{{ number_format($reservation->price, 2) }}</span>
                                </div>
                            </div>

                            @php
                                $canCancel = in_array($reservation->status, ['pending', 'approved', 'pending_payment']) 
                                             && now()->diffInHours($reservation->start_time, false) >= 24;
                            @endphp

                            <div class="mt-6 pt-5 border-t border-gray-100 flex flex-wrap items-center justify-center sm:justify-end gap-2 md:gap-3">
                                @if($canCancel)
                                    <button onclick="openRescheduleModal({{ $reservation->id }}, '{{ $reservation->start_time->format('Y-m-d') }}', '{{ $reservation->start_time->format('H:i') }}')" 
                                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-blue-50 text-blue-700 text-sm font-black rounded-xl hover:bg-blue-100 transition gap-2 border-2 border-blue-100 whitespace-nowrap">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                        Planla
                                    </button>
                                    <form method="POST" action="{{ route('reservations.cancel', $reservation->id) }}" class="flex-1 sm:flex-none inline" id="cancelForm-{{ $reservation->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmCancellation('cancelForm-{{ $reservation->id }}')" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-50 text-red-700 text-sm font-black rounded-xl hover:bg-red-100 transition gap-2 border-2 border-red-100 whitespace-nowrap">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            İptal Et
                                        </button>
                                    </form>
                                @elseif($reservation->status == 'cancelled')
                                    <p class="text-xs font-bold text-gray-400 bg-gray-50 px-4 py-2 rounded-lg border border-gray-100">İptal edildi</p>
                                @elseif($reservation->status == 'completed')
                                    <button onclick="openReviewModal({{ $reservation->business_id }}, '{{ $reservation->business->name }}')" 
                                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-yellow-50 text-yellow-700 text-sm font-black rounded-xl hover:bg-yellow-100 transition gap-2 border-2 border-yellow-100 whitespace-nowrap">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                        Değerlendir
                                    </button>
                                @endif

                                @if(in_array($reservation->status, ['approved', 'confirmed', 'completed']))
                                    <a href="{{ route('reservations.invoice', $reservation->id) }}" 
                                       class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gray-50 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-100 transition gap-2 border border-gray-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Dekont
                                    </a>
                                @endif
                                <a href="{{ route('messages.chat', $reservation->business->owner_id) }}" 
                                   class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-indigo-50 text-indigo-700 text-sm font-black rounded-xl hover:bg-indigo-100 transition gap-2 border-2 border-indigo-100 whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                    Mesaj
                                </a>
                                @if($reservation->status == 'pending_payment')
                                    <a href="{{ route('payment.checkout', $reservation->id) }}" 
                                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-primary text-white text-sm font-black rounded-xl border-2 border-white/20 hover:border-white transition gap-2 shadow-lg shadow-primary/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        Öde
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals remain mostly unchanged but wrapped for styling if necessary -->
<!-- Reschedule Modal -->
<dialog id="rescheduleModal" class="modal rounded-3xl shadow-2xl p-0 backdrop:bg-gray-900/50">
    <div class="bg-white p-8 w-full max-w-md rounded-3xl relative overflow-hidden">
        <h3 class="font-bold text-2xl mb-2 text-gray-900">Yeniden Planla</h3>
        <p class="text-gray-500 mb-6">Rezervasyonunuz için yeni bir tarih ve saat seçin.</p>
        
        <form id="rescheduleForm" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Yeni Tarih</label>
                <input type="date" id="reschedule_date" name="start_date" class="w-full px-4 py-3.5 rounded-xl border-gray-200 bg-gray-50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition outline-none font-medium" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Yeni Saat</label>
                <input type="time" id="reschedule_time" name="start_time_only" class="w-full px-4 py-3.5 rounded-xl border-gray-200 bg-gray-50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition outline-none font-medium" required>
            </div>
            <div class="flex gap-3 mt-8">
                <button type="submit" class="flex-1 bg-primary text-white font-bold py-3.5 rounded-xl hover:bg-primary-dark transition shadow-lg shadow-primary/30">Güncelle</button>
                <button type="button" onclick="document.getElementById('rescheduleModal').close()" class="flex-1 bg-white text-gray-700 font-bold py-3.5 rounded-xl hover:bg-gray-50 border border-gray-200 transition">İptal</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Review Modal -->
<dialog id="reviewModal" class="modal rounded-3xl shadow-2xl p-0 backdrop:bg-gray-900/50">
    <div class="bg-white p-8 w-full max-w-md rounded-3xl">
        <h3 class="font-bold text-2xl mb-1 text-gray-900" id="reviewBusinessName">Mekan Adı</h3>
        <p class="text-gray-500 text-sm mb-6">Deneyiminizi puanlayın ve yorum yapın.</p>
        
        <form id="reviewForm" method="POST" class="space-y-5">
            @csrf
            <!-- Star Rating -->
            <div class="flex justify-center flex-row-reverse gap-2 mb-6">
                 @for($i = 5; $i >= 1; $i--)
                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="peer hidden" required />
                    <label for="star{{ $i }}" class="text-gray-200 peer-checked:text-yellow-400 hover:text-yellow-400 cursor-pointer transition transform hover:scale-110">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </label>
                 @endfor
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Yorumunuz</label>
                <textarea name="comment" rows="4" class="w-full px-4 py-3.5 rounded-xl border-gray-200 bg-gray-50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition outline-none resize-none" placeholder="Mekan hakkındaki düşünceleriniz..."></textarea>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 bg-primary text-white font-bold py-3.5 rounded-xl hover:bg-primary-dark transition shadow-lg shadow-primary/30">Gönder</button>
                <button type="button" onclick="document.getElementById('reviewModal').close()" class="flex-1 bg-white text-gray-700 font-bold py-3.5 rounded-xl hover:bg-gray-50 border border-gray-200 transition">İptal</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Scripts remain unchanged but ensures consistency -->
<script>
    // ... existing scripts ...
    let currentReservationId = null;
    let currentReviewBusinessId = null;

    function openRescheduleModal(id, currentDate, currentTime) {
        currentReservationId = id;
        document.getElementById('reschedule_date').value = currentDate;
        document.getElementById('reschedule_time').value = currentTime;
        document.getElementById('rescheduleModal').showModal();
    }

    function openReviewModal(businessId, businessName) {
        currentReviewBusinessId = businessId;
        document.getElementById('reviewBusinessName').textContent = businessName;
        document.getElementById('reviewForm').reset();
        document.getElementById('reviewModal').showModal();
    }
    
    // Maintain existing event listeners...
    // Re-adding them here for clarity in the file rewrite
    document.getElementById('rescheduleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const date = document.getElementById('reschedule_date').value;
        const time = document.getElementById('reschedule_time').value;
        const startTime = date + ' ' + time + ':00';
        
        fetch(`/reservations/${currentReservationId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ start_time: startTime })
        })
        .then(response => response.json())
        .then(data => {
            // Using standard alert if toast not available, but toast preferred
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
        
        document.getElementById('rescheduleModal').close();
    });

    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(`/business/${currentReviewBusinessId}/reviews`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData 
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            document.getElementById('reviewModal').close();
            // Optional: Show success message
            window.location.reload(); // To possibly show reviewed state
        })
        .catch(error => console.error('Error:', error));
    });
    function confirmCancellation(formId) {
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Rezervasyonunuzu iptal etmek istediğinizden emin misiniz? Bu işlem geri alınamaz.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Evet, İptal Et',
            cancelButtonText: 'Vazgeç',
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'px-5 py-2.5 rounded-xl font-bold',
                cancelButton: 'px-5 py-2.5 rounded-xl font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endsection
