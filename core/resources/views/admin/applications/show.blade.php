@extends('layouts.app')

@section('title', 'Başvuru Detayı - Operasyon Paneli')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-[1400px] mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-8 border-b border-slate-200 mb-10 gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition-colors">YÖNETİM</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <a href="{{ route('admin.applications.index') }}" class="hover:text-purple-600 transition-colors">BAŞVURULAR</a>
                    <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M9 5l7 7-7 7"></path></svg>
                    <span>DETAY #{{ $application->id }}</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">İnceleme <span class="text-purple-600">Dosyası</span></h1>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('admin.applications.index') }}" class="px-6 py-3 bg-white hover:bg-slate-50 text-slate-500 hover:text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] transition-all border border-slate-200 shadow-sm active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left-long text-xs"></i> LİSTEYE DÖN
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Business Profile Card -->
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/40 p-10 overflow-hidden relative">
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-slate-50 rounded-full opacity-50 z-0"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-8 mb-10">
                            <div class="w-24 h-24 rounded-[2rem] bg-slate-900 flex items-center justify-center text-white text-4xl font-black shadow-2xl ring-8 ring-slate-50">
                                {{ substr($application->business_name, 0, 1) }}
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-2">{{ $application->business_name }}</h2>
                                <div class="flex items-center gap-4">
                                    <span class="px-3 py-1 bg-purple-50 text-purple-600 text-[10px] font-black rounded-lg border border-purple-100 uppercase tracking-widest">
                                        {{ $application->category->name ?? 'Kategori Yok' }}
                                    </span>
                                    <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400">
                                        <i class="fa-solid fa-calendar-check text-[9px]"></i>
                                        <span>GÖNDERİM: {{ $application->created_at->translatedFormat('d F Y, H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 font-mono">İLETİŞİM SORUMLUSU</label>
                                    <div class="text-[13px] font-bold text-slate-700 bg-slate-50 p-4 rounded-2xl border border-slate-100">{{ $application->email }}</div>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 font-mono">TELEFON NUMARASI</label>
                                    <div class="text-[13px] font-bold text-slate-700 bg-slate-50 p-4 rounded-2xl border border-slate-100">{{ $application->phone }}</div>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 font-mono">FAALİYET ADRESİ</label>
                                    <div class="text-[13px] font-bold text-slate-700 bg-slate-50 p-4 rounded-2xl border border-slate-100 min-h-[108px] leading-relaxed">{{ $application->address }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-10 border-t border-slate-100">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 font-mono">İŞLETME ÖZETİ & HEDEFLER</label>
                            <div class="text-[13px] text-slate-600 bg-slate-50/50 p-8 rounded-[2rem] border border-dashed border-slate-200 leading-loose italic">
                                "{{ $application->description }}"
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legal Information & Documents -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-lg shadow-slate-200/20 p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-[0.25em]">HUKUKİ VERİLER</h3>
                            <i class="fa-solid fa-gavel text-slate-100 text-2xl"></i>
                        </div>
                        <div class="space-y-6">
                            <div class="flex justify-between items-center py-4 border-b border-slate-50">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">TİCARET SİCİL NO</span>
                                <span class="text-sm font-black text-slate-900 font-mono tracking-tighter">{{ $application->trade_registry_no }}</span>
                            </div>
                            <div class="flex justify-between items-center py-4">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">VERGİ KİMLİK NO</span>
                                <span class="text-sm font-black text-slate-900 font-mono tracking-tighter">{{ $application->tax_id }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-lg shadow-slate-200/20 p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-[11px] font-black text-slate-900 uppercase tracking-[0.25em]">EKLİ BELGELER</h3>
                            <i class="fa-solid fa-file-shield text-slate-100 text-2xl"></i>
                        </div>
                        <div class="space-y-3">
                            @foreach(['trade_registry_document' => 'Ticaret Sicil Dosyası', 'tax_document' => 'Vergi Levhası (PDF)', 'license_document' => 'Çalışma Ruhsatı', 'id_document' => 'Yetkili Kimliği', 'bank_document' => 'Hesap Bildirimi'] as $field => $label)
                                <a href="{{ route('admin.applications.document', ['id' => $application->id, 'field' => $field]) }}" target="_blank" class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-900 hover:text-white rounded-2xl border border-slate-100 hover:border-slate-900 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <i class="fa-solid fa-file-pdf text-rose-500 group-hover:text-white text-xs transition-colors"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest">{{ $label }}</span>
                                    </div>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] opacity-20 group-hover:opacity-100 transition-opacity"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-8">
                <div class="bg-slate-900 rounded-[3rem] p-10 text-white shadow-2xl shadow-slate-900/40 sticky top-8 relative overflow-hidden">
                    <div class="absolute -right-20 -bottom-20 opacity-5">
                        <i class="fa-solid fa-shield-halved text-[15rem]"></i>
                    </div>

                    <div class="relative z-10">
                        <h2 class="text-xl font-black mb-8 tracking-tight">Karar <span class="text-purple-400">Merkezi</span></h2>
                        
                        @if($application->status === 'pending')
                            <form id="statusForm" action="{{ route('admin.applications.update', $application->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" id="statusInput">
                                
                                <div class="mb-10">
                                    <label class="block text-[9px] font-black text-white/40 uppercase tracking-[0.25em] mb-4 font-mono">DENETÇİ NOTU</label>
                                    <textarea name="admin_note" rows="4" class="w-full bg-white/5 border border-white/10 rounded-[1.5rem] p-6 text-[12px] text-white font-medium focus:bg-white/10 focus:border-purple-500 outline-none transition-all placeholder:text-white/20 resize-none" placeholder="Red gerekçesi veya onay notu ekleyin...">{{ $application->admin_note }}</textarea>
                                </div>

                                <div class="space-y-4">
                                    <button type="button" onclick="startApprovalProcess()" class="w-full py-5 bg-emerald-500 hover:bg-emerald-400 text-white font-black text-[11px] rounded-[1.5rem] uppercase tracking-[0.25em] transition-all shadow-xl shadow-emerald-500/20 active:scale-95 flex items-center justify-center gap-3">
                                        <i class="fa-solid fa-check-circle text-lg"></i> BAŞVURUYU ONAYLA
                                    </button>
                                    
                                    <button type="button" onclick="rejectApplication()" class="w-full py-5 bg-white/5 hover:bg-rose-500 text-white/60 hover:text-white font-black text-[11px] rounded-[1.5rem] border border-white/10 hover:border-rose-500 uppercase tracking-[0.25em] transition-all active:scale-95 flex items-center justify-center gap-3">
                                        <i class="fa-solid fa-times-circle text-lg"></i> TALEBİ REDDET
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center py-10 bg-white/5 rounded-[2rem] border border-white/10 mb-8 backdrop-blur-sm">
                                <div class="w-16 h-16 rounded-full bg-{{ $application->status == 'approved' ? 'emerald' : 'rose' }}-500/20 flex items-center justify-center mx-auto mb-6">
                                    <i class="fa-solid fa-{{ $application->status == 'approved' ? 'check-double' : 'ban' }} text-2xl text-{{ $application->status == 'approved' ? 'emerald' : 'rose' }}-500"></i>
                                </div>
                                <h3 class="text-sm font-black uppercase tracking-[0.3em] mb-2">İŞLEM SONUÇLANDI</h3>
                                <div class="text-[10px] font-bold text-white/40 uppercase tracking-widest italic leading-relaxed">
                                    Bu dosya <strong>{{ $application->status == 'approved' ? 'ONAY' : 'RED' }}</strong> ile kapatılmıştır.
                                </div>
                            </div>
                            
                            @if($application->admin_note)
                                <div class="bg-white/5 p-6 rounded-[1.5rem] border border-white/10">
                                    <label class="block text-[8px] font-black text-white/30 uppercase tracking-[0.2em] mb-3 font-mono">DENETÇİ KARAR NOTU</label>
                                    <p class="text-[12px] text-white/80 leading-relaxed font-medium italic">{{ $application->admin_note }}</p>
                                </div>
                            @endif
                        @endif

                        <div class="mt-12 pt-10 border-t border-white/10">
                            <div class="flex items-center justify-between text-[9px] font-black text-white/30 uppercase tracking-[0.3em]">
                                <span>SİSTEM DURUMU</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                    <span class="text-white/60">OPERASYONA AÇIK</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function startApprovalProcess() {
        const defaultEmail = "{{ $application->email }}";
        const defaultPassword = Math.random().toString(36).slice(-10);

        Swal.fire({
            title: '<div class="text-2xl font-black text-slate-900 tracking-tight mb-2">Hesap Yapılandırma</div>',
            html: `
                <div class="text-left px-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-8 text-center italic leading-relaxed">Onaylanan işletme için sisteme giriş bilgileri oluşturuluyor.</p>
                    
                    <div class="mb-6">
                        <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 font-mono">USER IDENTIFIER (EMAIL)</label>
                        <div class="relative">
                            <i class="fa-solid fa-at absolute left-4 top-4 text-slate-300"></i>
                            <input type="email" value="${defaultEmail}" disabled 
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 text-slate-500 text-sm font-bold rounded-2xl cursor-not-allowed">
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 font-mono">SECURE ACCESS KEY (PASSWORD)</label>
                        <div class="relative group">
                            <i class="fa-solid fa-key absolute left-4 top-4 text-slate-300 group-focus-within:text-purple-600 transition-colors"></i>
                            <input type="text" id="swal-password" value="${defaultPassword}"
                                class="w-full pl-12 pr-24 py-4 bg-white border border-slate-200 text-slate-900 text-sm font-bold rounded-2xl focus:ring-8 focus:ring-purple-500/5 focus:border-purple-500 transition-all outline-none">
                            <button type="button" onclick="document.getElementById('swal-password').value = Math.random().toString(36).slice(-10)"
                                class="absolute right-3 top-2.5 px-4 py-1.5 text-[9px] font-black text-purple-600 bg-purple-50 hover:bg-purple-600 hover:text-white rounded-xl transition-all uppercase tracking-widest border border-purple-100">
                                YENİLE
                            </button>
                        </div>
                    </div>
                </div>
            `,
            confirmButtonText: '<span class="px-8 py-3 text-[11px] font-black uppercase tracking-widest">YAPILANDIRMAYI TAMAMLA</span>',
            showCancelButton: true,
            cancelButtonText: '<span class="px-8 py-3 text-[11px] font-black uppercase tracking-widest text-slate-400">İPTAL</span>',
            buttonsStyling: false,
            background: '#ffffff',
            padding: '3rem',
            width: '32rem',
            customClass: {
                popup: 'rounded-[3rem] border-none shadow-2xl',
                confirmButton: 'bg-slate-900 text-white rounded-2xl mx-1 shadow-xl shadow-slate-900/10 hover:bg-purple-600 transition-colors',
                cancelButton: 'bg-slate-50 text-slate-400 rounded-2xl mx-1 hover:bg-slate-100 transition-colors'
            },
            preConfirm: () => {
                return {
                    password: document.getElementById('swal-password').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                confirmFinalApproval(result.value.password);
            }
        });
    }

    function confirmFinalApproval(password) {
        Swal.fire({
            title: '<div class="text-xl font-black text-slate-900 tracking-tight">Emin misiniz?</div>',
            html: '<p class="text-sm font-bold text-slate-500 uppercase tracking-widest leading-relaxed mt-4">İşletme hesabı oluşturulacak ve giriş bilgileri otomatik olarak e-posta ile gönderilecektir.</p>',
            icon: 'info',
            iconColor: '#8B5CF6',
            showCancelButton: true,
            confirmButtonText: '<span class="px-8 py-3 text-[11px] font-black uppercase tracking-widest">EVET, HESABI ONAYLA</span>',
            cancelButtonText: '<span class="px-8 py-3 text-[11px] font-black uppercase tracking-widest text-slate-400">VAZGEÇ</span>',
            buttonsStyling: false,
            background: '#ffffff',
            padding: '3rem',
            customClass: {
                popup: 'rounded-[3rem]',
                confirmButton: 'bg-emerald-500 text-white rounded-2xl mx-1 shadow-xl shadow-emerald-500/20 hover:bg-emerald-600 transition-colors',
                cancelButton: 'bg-slate-50 text-slate-400 rounded-2xl mx-1 hover:bg-slate-100 transition-colors'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('statusForm');
                document.getElementById('statusInput').value = 'approved';
                
                let passInput = document.createElement('input');
                passInput.type = 'hidden';
                passInput.name = 'custom_password';
                passInput.value = password;
                form.appendChild(passInput);

                form.submit();
            }
        });
    }

    function rejectApplication() {
        Swal.fire({
            title: '<div class="text-xl font-black text-slate-900 tracking-tight">Ret Kararı</div>',
            html: '<p class="text-sm font-bold text-slate-500 uppercase tracking-widest leading-relaxed mt-4">Bu işletme başvurusu reddedilecektir. Bu işlem geri alınamaz.</p>',
            icon: 'error',
            iconColor: '#F43F5E',
            showCancelButton: true,
            confirmButtonText: '<span class="px-8 py-3 text-[11px] font-black uppercase tracking-widest">EVET, REDDET</span>',
            cancelButtonText: '<span class="px-8 py-3 text-[11px] font-black uppercase tracking-widest text-slate-400">İPTAL</span>',
            buttonsStyling: false,
            background: '#ffffff',
            padding: '3rem',
            customClass: {
                popup: 'rounded-[3rem]',
                confirmButton: 'bg-rose-600 text-white rounded-2xl mx-1 shadow-xl shadow-rose-600/20 hover:bg-rose-700 transition-colors',
                cancelButton: 'bg-slate-50 text-slate-400 rounded-2xl mx-1 hover:bg-slate-100 transition-colors'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('statusInput').value = 'rejected';
                document.getElementById('statusForm').submit();
            }
        });
    }
</script>
@endpush
@endsection

