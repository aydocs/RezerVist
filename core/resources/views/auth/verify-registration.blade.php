@extends('layouts.app')

@section('title', 'E-posta Doğrulama - RezerVist')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-slate-50">
    <div class="max-w-md w-full">
        <!-- Brand Logo/Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-primary/10 rounded-3xl mb-6">
                <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 mb-2">E-postanı Doğrula</h1>
            <p class="text-slate-500 font-medium">
                <span class="font-bold text-slate-700">{{ $email }}</span> adresine 6 haneli bir doğrulama kodu gönderdik.
            </p>
        </div>

        <!-- Verification Card -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary/5 p-8 border border-slate-100 relative overflow-hidden">
            <!-- Timer Progress Bar -->
            <div id="countdown-progress" class="absolute top-0 left-0 h-1 bg-primary/20 w-full transition-all duration-1000"></div>

            <form action="{{ route('register.verify.submit') }}" method="POST" id="verify-form">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-8">
                    <label class="block text-center text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Doğrulama Kodu</label>
                    <div class="flex justify-between gap-2 sm:gap-4" id="otp-inputs">
                        @for($i = 0; $i < 6; $i++)
                            <input type="text" maxlength="1" 
                                class="otp-input w-full h-12 sm:h-16 text-center text-2xl font-black rounded-2xl border-2 border-slate-100 focus:border-primary focus:ring-0 transition-all outline-none text-slate-900 bg-slate-50 focus:bg-white"
                                required autocomplete="off">
                        @endfor
                    </div>
                    <input type="hidden" name="code" id="final-code">
                    @error('code')
                        <p class="text-red-500 text-sm font-bold mt-4 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black text-lg hover:bg-slate-800 transform active:scale-[0.98] transition-all shadow-lg shadow-slate-200">
                    Hesabımı Doğrula
                </button>
            </form>

            <!-- Resend Section -->
            <div class="mt-8 text-center">
                <p id="timer-text" class="text-sm font-bold text-slate-400">
                    Yeni kod için <span id="countdown" class="text-primary tabular-nums">180</span> saniye bekle
                </p>
                <button type="button" id="resend-btn" onclick="resendOTP()" 
                    class="hidden text-sm font-black text-primary hover:text-primary/80 transition-colors uppercase tracking-wider">
                    Kodu Tekrar Gönder
                </button>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('register') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">
                ← Bilgileri Değiştir veya Geri Dön
            </a>
        </div>
    </div>
</div>

<script>
    // OTP Input Logic
    const inputs = document.querySelectorAll('.otp-input');
    const finalInput = document.getElementById('final-code');
    const form = document.getElementById('verify-form');

    inputs.forEach((input, index) => {
        input.oninput = (e) => {
            if (e.target.value.length > 1) {
                e.target.value = e.target.value.slice(0, 1);
            }
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            updateFinalCode();
        };

        input.onkeydown = (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        };

        // Handle Paste
        input.onpaste = (e) => {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').slice(0, 6).split('');
            pasteData.forEach((char, i) => {
                if (inputs[i]) {
                    inputs[i].value = char;
                }
            });
            updateFinalCode();
            if (pasteData.length > 0) inputs[Math.min(pasteData.length, inputs.length - 1)].focus();
        };
    });

    function updateFinalCode() {
        let code = '';
        inputs.forEach(input => code += input.value);
        finalInput.value = code;
    }

    // Countdown Logic
    let timeLeft = 180;
    const countdownEl = document.getElementById('countdown');
    const timerText = document.getElementById('timer-text');
    const resendBtn = document.getElementById('resend-btn');
    const progressBar = document.getElementById('countdown-progress');

    const timer = setInterval(() => {
        timeLeft--;
        countdownEl.textContent = timeLeft;
        progressBar.style.width = `${(timeLeft / 180) * 100}%`;

        if (timeLeft <= 0) {
            clearInterval(timer);
            timerText.classList.add('hidden');
            resendBtn.classList.remove('hidden');
            progressBar.style.backgroundColor = '#ef4444'; // Red when expired
        }
    }, 1000);

    // Resend OTP Function
    async function resendOTP() {
        resendBtn.disabled = true;
        resendBtn.textContent = 'GÖNDERİLİYOR...';
        
        try {
            const response = await fetch('{{ route("register.resend-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: '{{ $email }}' })
            });
            
            const result = await response.json();
            
            if (result.success) {
                window.location.reload(); // Quickest way to reset timer and state
            } else {
                alert(result.message);
                resendBtn.disabled = false;
                resendBtn.textContent = 'KODU TEKRAR GÖNDER';
            }
        } catch (error) {
            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            resendBtn.disabled = false;
            resendBtn.textContent = 'KODU TEKRAR GÖNDER';
        }
    }
</script>
@endsection
