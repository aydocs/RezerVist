<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

class PhoneVerificationController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send verification code
     */
    public function sendCode(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'type' => 'in:registration,login'
        ]);

        $phone = $validated['phone'];
        $type = $validated['type'] ?? 'registration';

        // Rate limiting - 3 attempts per hour per phone
        $key = 'sms-verification:' . $phone;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Çok fazla deneme yaptınız. " . ceil($seconds / 60) . " dakika sonra tekrar deneyin."
            ], 429);
        }

        // For login, check if user exists
        if ($type === 'login') {
            $user = User::where('phone', $phone)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu telefon numarası ile kayıtlı kullanıcı bulunamadı.'
                ], 404);
            }
        }

        // Create verification record
        $verification = \App\Models\PhoneVerification::createForPhone($phone, $type);

        // Prepare message
        $message = "Rezervist doğrulama kodunuz: {$verification->code}\nBu kod 10 dakika geçerlidir.";
        
        // Send SMS via VatanSMS
        $sent = $this->smsService->send($phone, $message);

        if ($sent) {
            RateLimiter::hit($key, 3600); // 1 hour

            return response()->json([
                'success' => true,
                'message' => 'Doğrulama kodu gönderildi.',
                'expires_in' => 600 // 10 minutes
            ]);
        }

        // Clean up verification record if SMS failed
        if ($verification) {
            $verification->delete();
        }

        return response()->json([
            'success' => false,
            'message' => 'SMS gönderilemedi. Lütfen tekrar deneyin.'
        ], 500);
    }

    /**
     * Verify code
     */
    public function verifyCode(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:6'
        ]);

        $verification = \App\Models\PhoneVerification::where('phone', $validated['phone'])
            ->where('code', $validated['code'])
            ->where('verified', false)
            ->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz doğrulama kodu'
            ], 400);
        }

        if ($verification->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama kodu süresi dolmuş'
            ], 400);
        }

        // Mark as verified
        $verification->update(['verified' => true]);

        $result = ['success' => true];

        if ($result['success']) {
            // If this is for login, automatically log them in
            $user = User::where('phone', $validated['phone'])->first();
            if ($user) {
                // Mark phone as verified
                if (!$user->phone_verified_at) {
                    $user->update(['phone_verified_at' => now()]);
                }

                auth()->login($user);
                $request->session()->regenerate();

                return response()->json([
                    'success' => true,
                    'message' => 'Giriş başarılı',
                    'redirect' => url('/')
                ]);
            }

            return response()->json($result);
        }

        return response()->json($result, 400);
    }

    /**
     * Resend verification code
     */
    public function resendCode(Request $request)
    {
        return $this->sendCode($request);
    }
}
