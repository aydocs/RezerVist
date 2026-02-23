@extends('layouts.app')

@section('title', 'Başvuru Yönetimi - Operasyon Paneli')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1400px] mx-auto">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-8 border-b border-slate-200 mb-8 gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">YÖNETİM</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>İŞLETME BAŞVURULARI</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Kayıt <span class="text-purple-600">Talepleri</span></h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center bg-white border border-slate-200 p-1 rounded-2xl shadow-sm">
                    <a href="{{ route('admin.applications.index') }}" 
                        class="px-5 py-2 text-[10px] font-black rounded-xl transition-all uppercase tracking-widest {{ !request('status') ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">TÜMÜ</a>
                    <a href="{{ route('admin.applications.index', ['status' => 'pending']) }}" 
                        class="px-5 py-2 text-[10px] font-black rounded-xl transition-all uppercase tracking-widest {{ request('status') == 'pending' ? 'bg-amber-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">BEKLEYEN</a>
                    <a href="{{ route('admin.applications.index', ['status' => 'approved']) }}" 
                        class="px-5 py-2 text-[10px] font-black rounded-xl transition-all uppercase tracking-widest {{ request('status') == 'approved' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">ONAYLI</a>
                </div>
            </div>
        </div>

        <!-- Density-Optimized Applications Grid/List -->
        <div class="grid gap-4">
            @forelse($applications as $application)
                <div class="bg-white border border-slate-200 rounded-3xl p-6 hover:border-purple-200 hover:shadow-xl hover:shadow-slate-200/40 transition-all group relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 font-extrabold text-xl group-hover:bg-slate-900 group-hover:text-white group-hover:rotate-3 transition-all duration-500 shrink-0">
                            {{ substr($application->business_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1.5">
                                <h3 class="font-black text-slate-900 text-lg tracking-tight group-hover:text-purple-600 transition-colors">{{ $application->business_name }}</h3>
                                @if($application->status === 'pending')
                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[8px] font-black rounded-md border border-amber-100 uppercase tracking-widest animate-pulse">BEKLEMEDE</span>
                                @elseif($application->status === 'approved')
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[8px] font-black rounded-md border border-emerald-100 uppercase tracking-widest">ONAYLANDI</span>
                                @else
                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-[8px] font-black rounded-md border border-rose-100 uppercase tracking-widest">REDDEDİLDİ</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                <span class="flex items-center gap-2"><i class="fa-solid fa-folder-open text-[9px] text-purple-600/40"></i> {{ $application->category->name ?? 'BELİRTİLMEMİŞ' }}</span>
                                <span class="flex items-center gap-2"><i class="fa-solid fa-envelope-open text-[9px] text-slate-200"></i> {{ $application->email }}</span>
                                <span class="flex items-center gap-2"><i class="fa-solid fa-clock text-[9px] text-slate-200"></i> {{ $application->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ route('admin.applications.show', $application->id) }}" class="px-6 py-3 bg-white hover:bg-slate-50 text-slate-500 hover:text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] transition-all border border-slate-200 shadow-sm active:scale-95">
                            DETAYLI İNCELE
                        </a>
                        @if($application->status === 'pending')
                            <button onclick="confirmApprove({{ $application->id }})" class="px-6 py-3 bg-slate-900 hover:bg-purple-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] transition-all shadow-xl shadow-slate-900/10 active:scale-95 group">
                                <i class="fa-solid fa-bolt-lightning mr-2 text-amber-400 group-hover:scale-110 transition-transform"></i> HIZLI ONAY
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
                <div class="bg-white border border-slate-100 rounded-[2.5rem] p-32 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mb-6">
                        <i class="fa-solid fa-clipboard-question text-3xl text-slate-200"></i>
                    </div>
                    <p class="text-[12px] font-black text-slate-400 uppercase tracking-[0.4em]">ŞU AN AKTİF BİR BAŞVURU BULUNMUYOR</p>
                </div>
            @endforelse
        </div>

        <!-- Professional Pagination -->
        @if($applications->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $applications->links('vendor.pagination.console') }}
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmApprove(id) {
        Swal.fire({
            title: '<span class="text-xl font-black text-slate-900 tracking-tight">Onay İşlemi</span>',
            html: '<p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-4">Bu işletmeyi hızlıca onaylamak üzeresiniz. Emin misiniz?</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8B5CF6',
            cancelButtonColor: '#F1F5F9',
            confirmButtonText: '<span class="px-4 py-2 font-black uppercase text-[11px] tracking-widest">EVET, ONAYLA</span>',
            cancelButtonText: '<span class="px-4 py-2 font-black uppercase text-[11px] tracking-widest text-slate-500">İPTAL</span>',
            background: '#ffffff',
            borderRadius: '2rem',
            customClass: {
                confirmButton: 'rounded-2xl',
                cancelButton: 'rounded-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('quick-approve-' + id).submit();
            }
        });
    }
</script>
@endpush
@endsection
