<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WebAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Audit Log
            \App\Models\ActivityLog::logLogin(Auth::id());

            return redirect()->intended('/');
        }

        // Audit Log Failed
        \App\Models\ActivityLog::logFailedLogin($request->email);

        return back()->withErrors([
            'email' => 'Girdiğiniz bilgiler hatalı.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|unique:users,phone',
            'terms_accepted' => 'required|accepted',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ], [
            'terms_accepted.required' => 'Kullanım koşullarını ve gizlilik politikasını kabul etmelisiniz.',
            'terms_accepted.accepted' => 'Kullanım koşullarını ve gizlilik politikasını kabul etmelisiniz.',
            'referral_code.exists' => 'Girdiğiniz referans kodu geçersiz.',
            'phone.unique' => 'Bu telefon numarası zaten başka bir hesap tarafından kullanılıyor.',
        ]);

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        $email = $validated['email'];

        // Store registration data in cache for 3 minutes (180 seconds)
        $cacheKey = 'pending_registration_'.str_replace(['@', '.'], '_', $email);
        \Illuminate\Support\Facades\Cache::put($cacheKey, [
            'data' => $validated,
            'otp' => $otp,
            'attempts' => 0,
        ], now()->addSeconds(180));

        // Send OTP Email
        try {
            \Illuminate\Support\Facades\Mail::send('emails.midnight-otp', [
                'code' => $otp,
                'user_name' => $validated['name'],
                'user_avatar' => 'https://ui-avatars.com/api/?name='.urlencode($validated['name']).'&background=7c3aed&color=fff',
            ], function ($message) use ($email) {
                $message->to($email)->subject('RezerVist - Doğrulama Kodunuz');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Registration OTP Mail Error: '.$e->getMessage());

            return back()->with('error', 'E-posta gönderilemedi. Lütfen bilgilerinizi kontrol edin.');
        }

        // Redirect to verification page
        return redirect()->route('register.verify', ['email' => $email])
            ->with('status', 'Doğrulama kodu e-postanıza gönderildi. Lütfen 3 dakika içinde kodu girin.');
    }

    public function showVerifyForm(Request $request)
    {
        $email = $request->query('email');
        if (! $email) {
            return redirect()->route('register');
        }

        $cacheKey = 'pending_registration_'.str_replace(['@', '.'], '_', $email);
        if (! \Illuminate\Support\Facades\Cache::has($cacheKey)) {
            return redirect()->route('register')->with('error', 'Doğrulama süresi doldu veya oturum bulunamadı. Lütfen tekrar kayıt olun.');
        }

        return view('auth.verify-registration', compact('email'));
    }

    public function verifyRegistration(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $email = $request->email;
        $cacheKey = 'pending_registration_'.str_replace(['@', '.'], '_', $email);
        $cachedData = \Illuminate\Support\Facades\Cache::get($cacheKey);

        if (! $cachedData) {
            return redirect()->route('register')->with('error', 'Doğrulama süresi doldu. Lütfen tekrar kayıt olun.');
        }

        // Check attempts (max 5)
        if ($cachedData['attempts'] >= 5) {
            \Illuminate\Support\Facades\Cache::forget($cacheKey);

            return redirect()->route('register')->with('error', 'Çok fazla hatalı deneme yaptınız. Lütfen tekrar kayıt olun.');
        }

        if ($request->code != $cachedData['otp']) {
            $cachedData['attempts']++;
            \Illuminate\Support\Facades\Cache::put($cacheKey, $cachedData, now()->addSeconds(180));

            return back()->withErrors(['code' => 'Girdiğiniz kod hatalı. Lütfen tekrar deneyin.']);
        }

        // OTP Valid - Create the user
        $data = $cachedData['data'];

        $referredById = null;
        if (! empty($data['referral_code'])) {
            $referrer = User::where('referral_code', $data['referral_code'])->first();
            if ($referrer) {
                $referredById = $referrer->id;
            }
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'role' => 'user',
            'email_verified_at' => now(), // Email is now verified
            'referred_by_id' => $referredById,
        ]);

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget($cacheKey);

        // Login
        Auth::login($user);

        // Onboarding Notifications
        $user->notify(new \App\Notifications\OnboardingNotification('RezerVist\'e Hoş Geldiniz! Mekanları keşfetmeye hemen başlayın.', route('search.index')));

        // Audit Log
        \App\Models\ActivityLog::logUserCreated($user);

        return redirect('/')->with('success', 'Hesabınız başarıyla oluşturuldu ve doğrulandı!');
    }

    public function resendOTP(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;
        $cacheKey = 'pending_registration_'.str_replace(['@', '.'], '_', $email);
        $cachedData = \Illuminate\Support\Facades\Cache::get($cacheKey);

        if (! $cachedData) {
            return response()->json(['success' => false, 'message' => 'Oturum bulunamadı.'], 404);
        }

        // Generate NEW 6-digit OTP
        $newOtp = rand(100000, 999999);
        $cachedData['otp'] = $newOtp;
        $cachedData['attempts'] = 0;

        \Illuminate\Support\Facades\Cache::put($cacheKey, $cachedData, now()->addSeconds(180));

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::send('emails.midnight-otp', [
                'code' => $newOtp,
                'user_name' => $cachedData['data']['name'],
                'user_avatar' => 'https://ui-avatars.com/api/?name='.urlencode($cachedData['data']['name']).'&background=7c3aed&color=fff',
            ], function ($message) use ($email) {
                $message->to($email)->subject('RezerVist - Yeni Doğrulama Kodunuz');
            });

            return response()->json(['success' => true, 'message' => 'Yeni kod başarıyla gönderildi.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'E-posta gönderilemedi.'], 500);
        }
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('status', 'E-posta adresiniz kayıtlıysa bir kod gönderilecektir.');
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        $email = $user->email;

        // Store reset data in cache for 3 minutes (180 seconds)
        $cacheKey = 'password_reset_'.str_replace(['@', '.'], '_', $email);
        \Illuminate\Support\Facades\Cache::put($cacheKey, [
            'otp' => $otp,
            'attempts' => 0,
        ], now()->addSeconds(180));

        // Send Reset Email
        try {
            \Illuminate\Support\Facades\Mail::send('emails.aura-reset', [
                'code' => $otp,
                'user_name' => $user->name,
                'user_avatar' => $user->profile_photo_url,
            ], function ($message) use ($email) {
                $message->to($email)->subject('RezerVist - Şifre Sıfırlama Kodunuz');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Password Reset OTP Error: '.$e->getMessage());

            return back()->with('error', 'E-posta gönderilemedi.');
        }

        return redirect()->route('password.reset.verify', ['email' => $email])
            ->with('status', 'Şifre sıfırlama kodu e-postanıza gönderildi.');
    }

    public function showResetForm(Request $request)
    {
        $email = $request->query('email');
        if (! $email) {
            return redirect()->route('password.request');
        }

        $cacheKey = 'password_reset_'.str_replace(['@', '.'], '_', $email);
        if (! \Illuminate\Support\Facades\Cache::has($cacheKey)) {
            return redirect()->route('password.request')->with('error', 'Kodun süresi doldu. Lütfen tekrar talep edin.');
        }

        return view('auth.verify-reset-password', compact('email'));
    }

    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->email;
        $cacheKey = 'password_reset_'.str_replace(['@', '.'], '_', $email);
        $cachedData = \Illuminate\Support\Facades\Cache::get($cacheKey);

        if (! $cachedData) {
            return redirect()->route('password.request')->with('error', 'Süre doldu. Lütfen tekrar deneyin.');
        }

        if ($request->code != $cachedData['otp']) {
            $cachedData['attempts']++;
            \Illuminate\Support\Facades\Cache::put($cacheKey, $cachedData, now()->addSeconds(180));

            return back()->withErrors(['code' => 'Girdiğiniz kod hatalı.']);
        }

        // Code valid - Update password
        $user = User::where('email', $email)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        \Illuminate\Support\Facades\Cache::forget($cacheKey);

        return redirect()->route('login')->with('success', 'Şifreniz başarıyla güncellendi. Yeni şifrenizle giriş yapabilirsiniz.');
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($userId) {
            \App\Models\ActivityLog::logLogout($userId);
        }

        return redirect('/');
    }
}
