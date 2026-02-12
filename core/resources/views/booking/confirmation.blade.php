@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    window.onload = function() {
        var duration = 3 * 1000;
        var end = Date.now() + duration;

        (function frame() {
            confetti({
                particleCount: 5,
                angle: 60,
                spread: 55,
                origin: { x: 0 },
                colors: ['#7c3aed', '#a855f7', '#d8b4fe']
            });
            confetti({
                particleCount: 5,
                angle: 120,
                spread: 55,
                origin: { x: 1 },
                colors: ['#7c3aed', '#a855f7', '#d8b4fe']
            });

            if (Date.now() < end) {
                requestAnimationFrame(frame);
            }
        }());
    };
</script>
<div class="bg-gray-50 min-h-screen py-20 flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-xl border border-gray-100 text-center">
        
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-2">Rezervasyon Başarılı!</h1>
        <p class="text-gray-500 mb-8">Rezervasyonunuz alınmıştır. İşletme onayı sonrası size bildirim gönderilecektir.</p>

        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 text-left mb-8">
            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200">
                <span class="text-sm text-gray-500">Rezervasyon No</span>
                <span class="font-mono font-bold text-gray-900">#{{ $reservation->id }}</span>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Mekan</span>
                    <span class="font-medium text-gray-900">{{ $reservation->business->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tarih</span>
                    <span class="font-medium text-gray-900">{{ $reservation->start_time->format('d.m.Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Saat</span>
                    <span class="font-medium text-gray-900">{{ $reservation->start_time->format('H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <a href="/profile/reservations" class="bg-primary text-white py-3 rounded-xl font-bold hover:bg-primary/90 transition shadow-lg shadow-primary/30">Rezervasyonlarımı Gör</a>
            <a href="/" class="text-gray-500 hover:text-gray-900 font-medium py-2">Ana Sayfaya Dön</a>
        </div>
    </div>
</div>
@endsection
