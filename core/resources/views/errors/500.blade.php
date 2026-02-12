@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-orange-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-red-600">500</h1>
            <div class="h-1 w-24 bg-red-600 mx-auto mt-4 rounded-full"></div>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Bir Şeyler Yanlış Gitti</h2>
        <p class="text-gray-600 mb-8">Sunucuda bir hata oluştu. Teknik ekibimiz durumdan haberdar edildi.</p>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-8 text-left">
            <p class="text-sm text-yellow-800"><strong>İpucu:</strong> Sayfayı yenilemeyi deneyin veya birkaç dakika sonra tekrar kontrol edin.</p>
        </div>
        
        <a href="/" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-primary/90 transition shadow-lg shadow-primary/30 inline-block">
            Ana Sayfaya Dön
        </a>
    </div>
</div>
@endsection
