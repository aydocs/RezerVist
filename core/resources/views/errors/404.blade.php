@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-primary">404</h1>
            <div class="h-1 w-24 bg-primary mx-auto mt-4 rounded-full"></div>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Sayfa Bulunamadı</h2>
        <p class="text-gray-600 mb-8">Aradığınız sayfa taşınmış, silinmiş veya hiç var olmamış olabilir.</p>
        
        <div class="flex gap-4 justify-center">
            <a href="/" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-primary/90 transition shadow-lg shadow-primary/30">
                Ana Sayfaya Dön
            </a>
            <a href="/search" class="bg-white text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-50 transition border border-gray-200">
                Arama Yap
            </a>
        </div>
    </div>
</div>
@endsection
