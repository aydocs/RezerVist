@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Güvenli Ödeme</h2>
            <p class="mt-2 text-sm text-gray-600">Rezervist.com ile Güvenli İşlem (Simülasyon)</p>
        </div>
        
        <div class="bg-blue-50 p-4 rounded-lg flex justify-between items-center">
            <span class="font-bold text-blue-800">Tutar:</span>
            <span class="font-bold text-2xl text-blue-900">{{ number_format($data['amount'], 2) }} ₺</span>
        </div>

        <div class="space-y-4">
            <div class="border rounded-lg p-4 animate-pulse bg-gray-50">
                <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
            </div>
            <p class="text-center text-sm text-gray-500">3D Secure Doğrulaması yapılıyor...</p>
        </div>

        <form id="autoSubmitForm" action="{{ route('payment.callback') }}" method="POST">
            @csrf
            <input type="hidden" name="reservation_id" value="{{ $data['id'] }}">
            <input type="hidden" name="status" value="success">
            
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-1000 ease-in-out transform hover:scale-105">
                Ödemeyi Tamamla
            </button>
        </form>
    </div>
</div>

<script>
    // Auto-submit simulation after delay
    setTimeout(() => {
        // document.getElementById('autoSubmitForm').submit();
    }, 2000);
</script>
@endsection
