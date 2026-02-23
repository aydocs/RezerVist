@extends('layouts.app')

@section('title', 'Admin Profili - Sistem Güvenliği')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1200px] mx-auto space-y-10 animate-fadeIn">
        
        <!-- Premium Command Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-8 border-b border-slate-200 gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5 hover:text-purple-600 transition-colors"><i class="fa-solid fa-bolt-lightning"></i> YÖNETİM</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>YÖNETİCİ PROFİLİ</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Hesap <span class="text-purple-600">Ayarları</span></h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="px-5 py-2.5 bg-white border border-slate-200 text-slate-400 text-[10px] font-black rounded-2xl uppercase tracking-[0.2em] flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-shield-halved text-purple-600"></i>
                    GÜVENLİ OTURUM AKTİF
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-slate-900 border border-slate-800 text-white px-8 py-5 rounded-[2rem] flex items-center justify-between shadow-2xl animate-fadeIn">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-500">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.2em]">SİSTEM GÜNCELLENDİ</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-500 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Sidebar / Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/20 overflow-hidden sticky top-8">
                    <div class="h-32 bg-slate-900 relative">
                        <div class="absolute inset-0 opacity-10">
                            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                                <path d="M0 0 L100 100 M0 100 L100 0" stroke="white" stroke-width="0.5" />
                            </svg>
                        </div>
                    </div>
                    <div class="px-8 pb-10 text-center -mt-12 relative">
                        <div class="relative inline-block mb-6 group">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-[2rem] object-cover border-4 border-white shadow-2xl mx-auto ring-1 ring-slate-100">
                            @else
                                <div class="w-24 h-24 rounded-[2rem] bg-purple-600 flex items-center justify-center text-white text-4xl font-black border-4 border-white shadow-2xl mx-auto ring-1 ring-slate-100">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="absolute -bottom-1 -right-1">
                                @csrf
                                <label for="photo" class="bg-slate-900 text-white w-9 h-9 rounded-2xl cursor-pointer hover:bg-purple-600 transition-all shadow-xl flex items-center justify-center border-2 border-white group-hover:scale-110">
                                    <i class="fa-solid fa-camera-retro text-[11px]"></i>
                                </label>
                                <input type="file" name="photo" id="photo" class="hidden" onchange="this.form.submit()">
                            </form>
                        </div>
                        
                        <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase">{{ $user->name }}</h2>
                        <div class="inline-flex items-center px-4 py-1.5 rounded-xl text-[9px] font-black bg-slate-900 text-white uppercase tracking-[0.3em] mt-3 border border-slate-800 shadow-lg">
                             SİSTEM YÖNETİCİSİ
                        </div>
                        
                        <div class="mt-10 space-y-4 pt-10 border-t border-slate-100 text-left">
                            <div class="group p-4 bg-slate-50 border border-slate-100 rounded-2xl hover:border-purple-200 hover:bg-white transition-all">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5 font-mono">PRIMARY REACH</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all">
                                        <i class="fa-solid fa-paper-plane text-[9px]"></i>
                                    </div>
                                    <p class="text-[11px] font-black text-slate-700 truncate tracking-tight">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="group p-4 bg-slate-50 border border-slate-100 rounded-2xl hover:border-purple-200 hover:bg-white transition-all">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5 font-mono">ACTIVATED ON</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all">
                                        <i class="fa-solid fa-calendar-check text-[9px]"></i>
                                    </div>
                                    <p class="text-[11px] font-black text-slate-700 tracking-tight uppercase">{{ $user->created_at->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Identity & Contact Intelligence -->
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/20 overflow-hidden">
                    <div class="px-10 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-id-card-clip text-slate-400"></i>
                            <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em]">HESAP BİLGİ MERKEZİ</h3>
                        </div>
                        <span class="text-[9px] font-black text-slate-300 tracking-widest font-mono">SEC-ID: {{ hash('xxh3', $user->id) }}</span>
                    </div>
                    <div class="p-10">
                        <form id="profileForm" action="{{ route('profile.update') }}" method="POST" class="space-y-10">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1 font-mono">FULL LEGAL NAME</label>
                                    <div class="relative group">
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-[1.25rem] focus:bg-white focus:ring-8 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-black text-slate-900 text-[11px] outline-none">
                                        <i class="fa-solid fa-user absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-purple-600 transition-colors"></i>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1 font-mono">SECURE PHONE LINE</label>
                                    <div class="relative group">
                                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-[1.25rem] focus:bg-white focus:ring-8 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-black text-slate-900 text-[11px] outline-none">
                                        <i class="fa-solid fa-phone-shield absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-purple-600 transition-colors"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-10 border-t border-slate-100 relative">
                                <div class="absolute -top-3 left-0 px-4 bg-white text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] font-mono">CRYPTOGRAPHIC ACCESS CONTROL</div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1 font-mono">CURRENT PASS</label>
                                        <input type="password" name="current_password" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-[1.25rem] focus:bg-white focus:ring-8 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-black text-slate-900 text-[11px] outline-none placeholder:text-slate-300" placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1 font-mono">NEW CREDENTIAL</label>
                                        <input type="password" name="password" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-[1.25rem] focus:bg-white focus:ring-8 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-black text-slate-900 text-[11px] outline-none placeholder:text-slate-300" placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1 font-mono">CONFIRM NEW</label>
                                        <input type="password" name="password_confirmation" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-[1.25rem] focus:bg-white focus:ring-8 focus:ring-purple-500/5 focus:border-purple-500 transition-all font-black text-slate-900 text-[11px] outline-none placeholder:text-slate-300" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-8">
                                <button type="button" onclick="confirmSave()" class="px-10 py-4 bg-slate-900 text-white text-[10px] font-black rounded-[1.5rem] hover:bg-purple-600 transition-all uppercase tracking-[0.3em] shadow-2xl shadow-slate-900/20 active:scale-[0.98] flex items-center gap-3 group">
                                    <i class="fa-solid fa-lock-open opacity-50 group-hover:rotate-12 transition-transform"></i>
                                    DEĞİŞİKLİKLERİ YAYINLA
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmSave() {
    Swal.fire({
        title: 'GÜVENLİ ONAY',
        text: "Profil bilgileriniz merkezi sistemde güncellenecektir. Devam edilsin mi?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0f172a',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'ONAYLIYORUM',
        cancelButtonText: 'İPTAL',
        background: '#ffffff',
        padding: '3rem',
        customClass: {
            container: 'backdrop-blur-sm',
            popup: 'rounded-[3rem] border border-slate-100 shadow-2xl',
            title: 'text-2xl font-black text-slate-900 tracking-tighter',
            htmlContainer: 'text-[13px] font-bold text-slate-500 leading-relaxed uppercase tracking-tight',
            confirmButton: 'rounded-2xl font-black text-[10px] uppercase tracking-[0.3em] px-8 py-4 shadow-xl shadow-slate-900/10',
            cancelButton: 'rounded-2xl font-black text-[10px] uppercase tracking-[0.3em] px-8 py-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('profileForm').submit();
        }
    });
}
</script>
@endsection
