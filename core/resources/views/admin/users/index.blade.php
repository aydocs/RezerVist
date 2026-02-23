@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1700px] mx-auto space-y-8">
        
        <!-- Professional Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b pb-8 border-slate-200">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">Yönetim</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Kullanıcı Operasyonları</span>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>Dizin</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Kullanıcı <span class="text-purple-600">Veritabanı</span></h1>
            </div>
            
            <div class="flex items-center gap-6 mt-4 md:mt-0">
                <div class="flex flex-col items-end">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Toplam Hesap</span>
                    <span class="text-2xl font-black text-slate-900 leading-none tracking-tighter">{{ number_format($totalUsers) }}</span>
                </div>
                <div class="h-10 w-px bg-slate-200"></div>
                <div class="flex items-center gap-4">
                    <div class="group">
                        <span class="block text-[8px] font-black text-purple-600/60 uppercase tracking-widest leading-none mb-1 group-hover:text-purple-600 transition-colors">İşletmeci</span>
                        <span class="text-sm font-black text-slate-700 leading-none tracking-tight">{{ number_format($businessUsersCount) }}</span>
                    </div>
                    <div class="group text-right">
                        <span class="block text-[8px] font-black text-emerald-600/60 uppercase tracking-widest leading-none mb-1 group-hover:text-emerald-600 transition-colors">Müşteri</span>
                        <span class="text-sm font-black text-slate-700 leading-none tracking-tight">{{ number_format($customerUsersCount) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Filters -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm" x-data="{ searchQuery: '{{ request('search') }}' }">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1 relative">
                    <input type="text" name="search" x-model="searchQuery" placeholder="İsim, e-posta veya telefon numarası..." 
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[11px] font-black text-slate-700 placeholder:text-slate-400 focus:ring-4 focus:ring-purple-500/5 focus:border-purple-500 transition-all outline-none uppercase tracking-tight">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
                </div>
                <div class="flex flex-wrap gap-2">
                    <select name="role" class="px-6 py-3 bg-slate-50 border border-slate-200 rounded-xl text-[10px] font-black text-slate-700 uppercase tracking-widest outline-none focus:border-purple-500 transition-all cursor-pointer">
                        <option value="">Tüm Roller</option>
                        <option value="business" {{ request('role') == 'business' ? 'selected' : '' }}>İşletme Yetkilisi</option>
                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Müşteri Portföyü</option>
                    </select>
                    <button type="submit" class="px-8 py-3 bg-slate-900 hover:bg-purple-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-slate-900/10 active:scale-95">
                        Filtreleri Uygula
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left mr-2 opacity-50"></i> Temizle
                    </a>
                </div>
            </form>
        </div>

        <!-- Professional Table View -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Kullanıcı Tanımı</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Yetki Seviyesi</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Doğrulama</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Üyelik Tarihi</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right whitespace-nowrap">Yönetim</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/30 transition-all group">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 flex items-center justify-center font-black text-[11px] group-hover:bg-slate-900 group-hover:text-white group-hover:border-slate-900 transition-all duration-300">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-slate-900 tracking-tight">{{ $user->name }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 lowercase tracking-tight">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    @php
                                        $roleBadge = $user->role === 'business' 
                                            ? 'bg-purple-50 text-purple-600 border-purple-100' 
                                            : 'bg-slate-50 text-slate-500 border-slate-100';
                                        $roleLabel = $user->role === 'business' ? 'İşletmeci' : 'Müşteri';
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md text-[8px] font-black uppercase tracking-widest border {{ $roleBadge }}">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $user->email_verified_at ? 'bg-emerald-500 shadow-sm shadow-emerald-500/30' : 'bg-slate-300' }}"></div>
                                        <span class="text-[9px] font-black uppercase tracking-[0.15em] {{ $user->email_verified_at ? 'text-slate-700' : 'text-slate-400' }}">
                                            {{ $user->email_verified_at ? 'Onaylı' : 'Bekliyor' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <p class="text-[10px] font-black text-slate-700 uppercase tracking-tighter">{{ $user->created_at->format('d/m/Y') }}</p>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $user->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all group/edit" title="Profili Düzenle">
                                            <i class="fa-solid fa-user-gear text-[11px]"></i>
                                        </a>
                                        <button onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all group/delete" title="Sistemden Kaldır">
                                            <i class="fa-solid fa-user-minus text-[11px]"></i>
                                        </button>
                                        <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-32 text-center opacity-20">
                                    <i class="fa-solid fa-users-slash text-5xl mb-4"></i>
                                    <p class="text-[9px] font-black uppercase tracking-[0.2em]">Kullanıcı havuzu boş</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="flex justify-center pt-4">
                {{ $users->links('vendor.pagination.console') }}
            </div>
        @endif

    </div>
</div>

<script>
function confirmDelete(userId, userName) {
    Swal.fire({
        title: 'KALICI OLARAK SİLİNSİN Mİ?',
        text: userName.toUpperCase() + " isimli kullanıcı ve ilişkili tüm veriler sistemden temizlenecektir.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0F172A',
        cancelButtonColor: '#F1F5F9',
        confirmButtonText: 'EVET, SİL',
        cancelButtonText: 'İPTAL ET',
        customClass: {
            title: 'text-sm font-black text-slate-900 tracking-widest',
            htmlContainer: 'text-[11px] font-bold text-slate-500 uppercase tracking-tight',
            confirmButton: 'px-8 py-3 rounded-xl uppercase tracking-widest text-[10px] font-black shadow-lg shadow-slate-900/10',
            cancelButton: 'px-8 py-3 rounded-xl uppercase tracking-widest text-[10px] font-black text-slate-400 border-none'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + userId).submit();
        }
    });
}
</script>

<style>
.pagination { @apply flex items-center justify-center gap-2; }
.page-item .page-link { 
    @apply w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-400 text-[10px] font-black transition-all hover:bg-slate-50 hover:text-slate-900 shadow-sm;
}
.page-item.active .page-link {
    @apply bg-purple-600 border-purple-600 text-white shadow-lg shadow-purple-600/20;
}
.page-item.disabled .page-link { @apply opacity-50 cursor-not-allowed bg-slate-50; }
</style>
@endsection
