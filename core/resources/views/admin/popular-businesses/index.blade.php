@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mb-2">
                    En Popüler İşletmeler
                </h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Rezervasyon sayılarına göre işletme sıralaması
                </p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 px-5 py-2.5 text-gray-600 hover:text-white bg-white hover:bg-gradient-to-r hover:from-purple-600 hover:to-purple-700 rounded-xl transition-all shadow-sm hover:shadow-lg border border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="font-semibold">Panele Dön</span>
            </a>
        </div>

        <!-- Businesses Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Sıra</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">İşletme</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rezervasyon Sayısı</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Puan</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($businesses as $index => $business)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold text-sm shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all">
                                            #{{ $businesses->firstItem() + $index }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($business->image)
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-lg object-cover shadow-sm" src="{{ asset('storage/' . $business->image) }}" alt="">
                                            </div>
                                        @else
                                            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-lg">
                                                {{ substr($business->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">{{ $business->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $business->location ?? 'Konum belirtilmemiş' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-50 text-purple-700">
                                        {{ $business->category->name ?? 'Genel' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $business->reservations_count }}</div>
                                    <div class="text-xs text-gray-500">Toplam Rezervasyon</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        <div class="flex">
                                            @for($i = 0; $i < 5; $i++)
                                                @if($i < floor($business->rating))
                                                    <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700 ml-1">{{ number_format($business->rating, 1) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-purple-600 hover:text-purple-900 font-semibold hover:underline">Detaylar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    Henüz işletme bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $businesses->links() }}
        </div>

    </div>
</div>
@endsection
