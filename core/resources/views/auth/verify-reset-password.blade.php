@extends('layouts.app')

@section('title', 'Şifre Sıfırlama - RezerVist')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-slate-50">
    <div class="max-w-md w-full">
        <!-- Brand Logo/Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-50 rounded-3xl mb-6">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 mb-2">Şifreni Sıfırla</h1>
            <p class="text-slate-500 font-medium">
                <span class="font-bold text-slate-700">{{ $email }}</span> için 6 haneli kodunla yeni şifreni belirle.
            </p>
        </div>

        <!-- Reset Card -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-red-500/5 p-8 border border-slate-100 relative overflow-hidden">
            <!-- Timer Progress Bar -->
            <div id="countdown-progress" class="absolute top-0 left-0 h-1 bg-red-500/20 w-full transition-all duration-1000"></div>

            <form action="{{ route('password.reset.submit') }}" method="POST" id="reset-form">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- OTP Code -->
                <div class="mb-8">
                    <label class="block text-center text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Doğrulama Kodu</label>
                    <div class="flex justify-between gap-2 sm:gap-4" id="otp-inputs">
                        @for($i = 0; $i < 6; $i++)
                            <input type="text" maxlength="1" 
                                class="otp-input w-full h-12 sm:h-16 text-center text-2xl font-black rounded-2xl border-2 border-slate-100 focus:border-red-500 focus:ring-0 transition-all outline-none text-slate-900 bg-slate-50 focus:bg-white"
                                required autocomplete="off">
                        @endfor
                    </div>
                    <input type="hidden" name="code" id="final-code">
                    @error('code')
                        <p class="text-red-500 text-sm font-bold mt-4 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password Fields -->
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Yeni Şifre</label>
                        <input type="password" name="password" required 
                            class="w-full px-6 py-4 rounded-2xl border-2 border-slate-100 focus:border-red-500 focus:ring-0 transition-all outline-none text-slate-900 bg-slate-50 focus:bg-white font-bold"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Şifre Tekrar</label>
                        <input type="password" name="password_confirmation" required 
                            class="w-full px-6 py-4 rounded-2xl border-2 border-slate-100 focus:border-red-500 focus:ring-0 transition-all outline-none text-slate-900 bg-slate-50 focus:bg-white font-bold"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full bg-red-600 text-white py-4 rounded-2xl font-black text-lg hover:bg-red-700 transform active:scale-[0.98] transition-all shadow-lg shadow-red-200">
                    Şifremi Güncelle
                </button>
            </form>

            <!-- Resend Section (Uses same logic as registration conceptually) -->
            <div class="mt-8 text-center">
                <p id="timer-text" class="text-sm font-bold text-slate-400">
                    Yeni kod için <span id="countdown" class="text-red-500 tabular-nums">180</span> saniye bekle
                </p>
                <form action="{{ route('password.email') }}" method="POST" id="resend-form" class="hidden">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="text-sm font-black text-red-500 hover:text-red-600 transition-colors uppercase tracking-wider">
                        Yeni Kod Gönder
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('password.request') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">
                ← Farklı Bir E-posta Dene
            </a>
        </div>
    </div>
</div>

<script>
    // OTP Input Logic (Identical to registration)
    const inputs = document.querySelectorAll('.otp-input');
    const finalInput = document.getElementById('final-code');

    inputs.forEach((input, index) => {
        input.oninput = (e) => {
            if (e.target.value.length > 1) e.target.value = e.target.value.slice(0, 1);
            if (e.target.value.length === 1 && index < inputs.length - 1) inputs[index + 1].focus();
            updateFinalCode();
        };
        input.onkeydown = (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) inputs[index - 1].focus();
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
    const resendForm = document.getElementById('resend-form');
    const progressBar = document.getElementById('countdown-progress');

    const timer = setInterval(() => {
        timeLeft--;
        countdownEl.textContent = timeLeft;
        progressBar.style.width = `${(timeLeft / 180) * 100}%`;
        if (timeLeft <= 0) {
            clearInterval(timer);
            timerText.classList.add('hidden');
            resendForm.classList.remove('hidden');
        }
    }, 1000);
</script>
@endsection
