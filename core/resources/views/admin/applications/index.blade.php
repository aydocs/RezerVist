@extends('layouts.app')

@section('title', 'İşletme Başvuruları')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">İşletme Başvuruları</h1>
            <p class="text-gray-600">Yeni işletme başvurularımı inceleyin ve onaylayın</p>
        </div>

        <!-- Status Tabs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 mb-8 flex gap-2">
            <a href="{{ route('admin.applications.index') }}" 
               class="flex-1 px-6 py-3 rounded-xl text-center font-semibold transition-all flex items-center justify-center gap-2 {{ !request('status') ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Tümü ({{ $totalApplications }})
            </a>
            <a href="{{ route('admin.applications.index', ['status' => 'pending']) }}" 
               class="flex-1 px-6 py-3 rounded-xl text-center font-semibold transition-all flex items-center justify-center gap-2 {{ request('status') == 'pending' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Bekleyen ({{ $pendingCount }})
            </a>
            <a href="{{ route('admin.applications.index', ['status' => 'approved']) }}" 
               class="flex-1 px-6 py-3 rounded-xl text-center font-semibold transition-all flex items-center justify-center gap-2 {{ request('status') == 'approved' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Onaylanan ({{ $approvedCount }})
            </a>
            <a href="{{ route('admin.applications.index', ['status' => 'rejected']) }}" 
               class="flex-1 px-6 py-3 rounded-xl text-center font-semibold transition-all flex items-center justify-center gap-2 {{ request('status') == 'rejected' ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Reddedilen ({{ $rejectedCount }})
            </a>
        </div>

        <!-- Applications Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($applications as $application)
                <div class="bg-white rounded-2xl shadow-sm border-2 {{ $application->status === 'pending' ? 'border-amber-200' : ($application->status === 'approved' ? 'border-emerald-200' : 'border-red-200') }} hover:shadow-lg transition-all overflow-hidden group">
                    
                    <!-- Header -->
                    <div class="p-6 {{ $application->status === 'pending' ? 'bg-gradient-to-r from-amber-50 to-orange-50' : ($application->status === 'approved' ? 'bg-gradient-to-r from-emerald-50 to-green-50' : 'bg-gradient-to-r from-red-50 to-pink-50') }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $application->business_name }}</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    {{ $application->category->name ?? 'Kategori Yok' }}
                                </span>
                            </div>
                            <span class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide {{ $application->status === 'pending' ? 'bg-amber-500 text-white' : ($application->status === 'approved' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white') }}">
                                {{ $application->status === 'pending' ? 'Bekliyor' : ($application->status === 'approved' ? 'Onaylı' : 'Reddedildi') }}
                            </span>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-4">
                        <!-- Description -->
                        <div>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $application->description }}</p>
                        </div>

                        <!-- Contact Info -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">E-posta</p>
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $application->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Telefon</p>
                                <p class="text-sm font-medium text-gray-900">{{ $application->phone }}</p>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-xs text-gray-500 mb-1">Adres</p>
                            <p class="text-sm text-gray-900">{{ $application->address }}</p>
                        </div>

                        <!-- Submission Date -->
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $application->created_at->diffForHumans() }} başvuruldu
                        </div>

                        <!-- Admin Note -->
                        @if($application->admin_note)
                            <div class="p-3 bg-blue-50 border-l-4 border-blue-500 rounded">
                                <p class="text-xs font-semibold text-blue-900 mb-1">Admin Notu</p>
                                <p class="text-sm text-blue-800">{{ $application->admin_note }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                        <a href="{{ route('admin.applications.show', $application->id) }}" class="flex-1 px-4 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all font-semibold text-sm text-center hover:border-gray-400">
                            Detayları Gör
                        </a>
                        @if($application->status === 'pending')
                            <button onclick="document.getElementById('quick-approve-{{ $application->id }}').submit()" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl hover:shadow-lg hover:shadow-emerald-500/50 transition-all font-semibold text-sm">
                                Hızlı Onayla
                            </button>
                            <form id="quick-approve-{{ $application->id }}" method="POST" action="{{ route('admin.applications.update', $application->id) }}" class="hidden">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-12">
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Başvuru Bulunamadı</h3>
                        <p class="text-gray-500">{{ request('status') ? ucfirst(request('status')).' başvuru bulunamadı' : 'Henüz işletme başvurusu yok' }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($applications->hasPages())
            <div class="mt-8">
                {{ $applications->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
