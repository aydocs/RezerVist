@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Başvuru Detayı #{{ $application->id }}</h1>
            <a href="{{ route('admin.applications.index') }}" class="text-gray-500 hover:text-gray-900 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Listeye Dön
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">İşletme Bilgileri</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">İşletme Adı</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $application->business_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $application->category->name ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Açıklama</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $application->description }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Adres</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $application->address }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Telefon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $application->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">E-posta</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $application->email }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Yasal Bilgiler</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ticaret Sicil No</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded inline-block">{{ $application->trade_registry_no }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Vergi Kimlik No</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded inline-block">{{ $application->tax_id }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Belgeler</h2>
                    <ul class="space-y-3">
                        @foreach(['trade_registry_document' => 'Ticaret Sicil Belgesi', 'tax_document' => 'Vergi Levhası', 'license_document' => 'İşletme Ruhsatı', 'id_document' => 'Kimlik Belgesi', 'bank_document' => 'Banka Bilgileri'] as $field => $label)
                        <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
                            </div>
                            <a href="{{ Storage::url($application->$field) }}" target="_blank" class="text-primary hover:text-purple-800 text-sm font-bold flex items-center">
                                Görüntüle
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">İşlem Yap</h2>
                    
                    @if($application->status === 'pending')
                    <form id="statusForm" action="{{ route('admin.applications.update', $application->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" id="statusInput">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Yönetici Notu</label>
                            <textarea name="admin_note" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm" placeholder="Red nedeni veya onay notu...">{{ $application->admin_note }}</textarea>
                        </div>


                        <div class="grid grid-cols-1 gap-3">
                            <button type="button" onclick="startApprovalProcess()" class="w-full flex justify-center items-center px-4 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition shadow-lg shadow-green-600/20">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Onayla
                            </button>
                            
                            <button type="button" onclick="rejectApplication()" class="w-full flex justify-center items-center px-4 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition shadow-lg shadow-red-600/20">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Reddet
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="text-center py-6 bg-gray-50 rounded-lg border border-gray-100">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="text-lg font-medium text-gray-900">İşlem Tamamlandı</h3>
                        <p class="text-sm text-gray-500 mt-1">Bu başvuru <strong>{{ $application->status == 'approved' ? 'Onaylandı' : 'Reddedildi' }}</strong>.</p>
                        @if($application->admin_note)
                            <div class="mt-4 text-left bg-white p-3 rounded border border-gray-200 text-sm">
                                <span class="font-bold block text-xs text-gray-500 uppercase">Yönetici Notu:</span>
                                {{ $application->admin_note }}
                            </div>
                        @endif
                    </div>
                    @endif

                    <script>
                        function startApprovalProcess() {
                            const defaultEmail = "{{ $application->email }}";
                            const defaultPassword = Math.random().toString(36).slice(-10); // Random string

                            Swal.fire({
                                title: 'İşletme Hesabı Oluştur',
                                html: `
                                    <div class="text-left">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                                        <input type="email" id="swal-email" class="swal2-input w-full" value="${defaultEmail}" disabled style="margin: 0 0 15px 0;">
                                        
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Şifre</label>
                                        <div class="relative">
                                            <input type="text" id="swal-password" class="swal2-input w-full" value="${defaultPassword}" style="margin: 0;">
                                            <button type="button" onclick="document.getElementById('swal-password').value = Math.random().toString(36).slice(-10)" class="absolute right-2 top-2 text-xs text-primary font-bold">Yenile</button>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">Bu bilgiler kullanıcıya e-posta ile gönderilecektir.</p>
                                    </div>
                                `,
                                showCancelButton: true,
                                confirmButtonText: 'Devam Et',
                                cancelButtonText: 'İptal',
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
                                title: 'Son Onay',
                                text: "Başvuru onaylanacak ve kullanıcıya giriş bilgileri gönderilecek. Emin misiniz?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#059669',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Evet, Onayla',
                                cancelButtonText: 'İptal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Inject password into hidden input (or create one dynamically)
                                    let form = document.getElementById('statusForm');
                                    
                                    // Hidden status input
                                    document.getElementById('statusInput').value = 'approved';
                                    
                                    // Hidden password input
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
                                title: 'Reddetmek İstediğinize Emin misiniz?',
                                text: "Bu işlem geri alınamaz.",
                                icon: 'error',
                                showCancelButton: true,
                                confirmButtonColor: '#DC2626',
                                cancelButtonColor: '#6B7280',
                                confirmButtonText: 'Evet, Reddet',
                                cancelButtonText: 'İptal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('statusInput').value = 'rejected';
                                    document.getElementById('statusForm').submit();
                                }
                            });
                        }
                    </script>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-xs text-center text-gray-500">
                            Durum: 
                            @if($application->status == 'pending')
                                <span class="font-bold text-yellow-600">Onay Bekliyor</span>
                            @elseif($application->status == 'approved')
                                <span class="font-bold text-green-600">Onaylandı</span>
                            @else
                                <span class="font-bold text-red-600">Reddedildi</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
