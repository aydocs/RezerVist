<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Iyzipay\Options;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Currency;
use Iyzipay\Model\Locale;
use Iyzipay\Model\PaymentGroup;

class WalletController extends Controller
{
    private function getOptions()
    {
        $options = new Options();
        $options->setApiKey(config('services.iyzico.api_key'));
        $options->setSecretKey(config('services.iyzico.secret_key'));
        $options->setBaseUrl(config('services.iyzico.base_url', 'https://sandbox-api.iyzipay.com'));
        return $options;
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');
        
        $transactions = $user->walletTransactions()->latest()->paginate(10);
        
        // Comprehensive Statistics
        $totalLoaded = $user->walletTransactions()->where('type', 'topup')->where('status', 'success')->sum('amount');
        $totalSpent = abs($user->walletTransactions()->where('type', 'payment')->where('status', 'success')->sum('amount'));
        $netBalance = $totalLoaded - $totalSpent;

        // Monthly Performance (Current Month)
        $monthlyLoaded = $user->walletTransactions()
            ->where('type', 'topup')
            ->where('status', 'success')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
            
        $monthlySpent = abs($user->walletTransactions()
            ->where('type', 'payment')
            ->where('status', 'success')
            ->whereMonth('created_at', now()->month)
            ->sum('amount'));

        // Count of transactions for activity heat
        $activityCount = $user->walletTransactions()->whereMonth('created_at', now()->month)->count();
        
        return view('profile.wallet.index', compact(
            'user', 
            'transactions', 
            'totalLoaded', 
            'totalSpent', 
            'netBalance', 
            'monthlyLoaded', 
            'monthlySpent',
            'activityCount'
        ));
    }

    public function initiateTopup(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:10|max:100000']);

        $user = Auth::user();
        $amount = $request->amount;

        $iyziRequest = new CreateCheckoutFormInitializeRequest();
        $iyziRequest->setLocale(Locale::TR);
        $iyziRequest->setConversationId(uniqid('wallet_'));
        $iyziRequest->setPrice($amount);
        $iyziRequest->setPaidPrice($amount);
        $iyziRequest->setCurrency(Currency::TL);
        $iyziRequest->setBasketId("W" . $user->id . "_" . time());
        $iyziRequest->setPaymentGroup(PaymentGroup::PRODUCT);
        $iyziRequest->setCallbackUrl(route('wallet.callback'));

        // Identity Bypass Logic: Use User data if exists, otherwise solid placeholders
        $buyer = new Buyer();
        $buyer->setId($user->id);
        $buyer->setName($user->name ?: 'Degerli');
        $buyer->setSurname($user->name ?: 'Musteri');
        $buyer->setEmail($user->email);
        $buyer->setGsmNumber($user->phone ?: '+905555555555');
        $buyer->setIdentityNumber('11111111111'); // Standard bypass
        $buyer->setRegistrationAddress($user->address ?: 'Istanbul');
        $buyer->setIp($request->ip());
        $buyer->setCity($user->city ?: 'Istanbul');
        $buyer->setCountry($user->country ?: 'Turkey');
        $iyziRequest->setBuyer($buyer);

        $address = new Address();
        $address->setContactName($user->name ?: 'Degerli Musteri');
        $address->setCity($user->city ?: 'Istanbul');
        $address->setCountry($user->country ?: 'Turkey');
        $address->setAddress($user->address ?: 'Istanbul');
        $iyziRequest->setShippingAddress($address);
        $iyziRequest->setBillingAddress($address);

        $item = new BasketItem();
        $item->setId("WALLET_TOPUP");
        $item->setName("Cüzdan Bakiye Yükleme");
        $item->setCategory1("Finans");
        $item->setItemType(BasketItemType::VIRTUAL);
        $item->setPrice($amount);
        $iyziRequest->setBasketItems([$item]);

        $checkoutForm = CheckoutFormInitialize::create($iyziRequest, $this->getOptions());

        if ($checkoutForm->getStatus() == 'success') {
            \App\Models\ActivityLog::logActivity('wallet_topup_initiated', "Cüzdan yükleme başlatıldı: {$amount} TL", ['amount' => $amount], $user->id);
            return response()->json(['status' => 'success', 'url' => $checkoutForm->getPaymentPageUrl()]);
        }

        return response()->json(['status' => 'error', 'message' => $checkoutForm->getErrorMessage()], 400);
    }

    public function callback(Request $request)
    {
        $token = $request->input('token');
        $status = 'error';
        $message = 'İşlem başarısız.';
        $userId = null;

        if ($token) {
            $iyziRequest = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
            $iyziRequest->setLocale(Locale::TR);
            $iyziRequest->setToken($token);

            $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($iyziRequest, $this->getOptions());

            if ($checkoutForm->getStatus() == 'success' && $checkoutForm->getPaymentStatus() == 'SUCCESS') {
                $basketId = $checkoutForm->getBasketId();
                if ($basketId && str_starts_with($basketId, 'W')) {
                    $parts = explode('_', substr($basketId, 1));
                    $userId = $parts[0] ?? null;
                }

                $user = $userId ? \App\Models\User::find($userId) : null;
                
                if ($user) {
                    $amount = $checkoutForm->getPrice();
                    $referenceId = $checkoutForm->getPaymentId();
                    
                    if (!WalletTransaction::where('reference_id', $referenceId)->exists()) {
                        DB::transaction(function () use ($user, $amount, $checkoutForm, $referenceId) {
                            $user->increment('balance', $amount);
                            $txn = WalletTransaction::create([
                                'user_id' => $user->id,
                                'amount' => $amount,
                                'type' => 'topup',
                                'status' => 'success',
                                'description' => 'Cüzdan Bakiye Yükleme',
                                'reference_id' => $referenceId,
                                'meta_data' => $checkoutForm->getRawResult(),
                            ]);
                            
                            // Log Activity
                            \App\Models\ActivityLog::logWalletTransaction($txn, 'success');
                        });
                        $status = 'success';
                        $message = 'Bakiye başarıyla yüklendi.';
                    } else {
                        $status = 'success';
                        $message = 'İşlem zaten işlendi.';
                    }
                }
            } else {
                $message = $checkoutForm->getErrorMessage();
                // Log Failure
                if ($userId) {
                    \App\Models\ActivityLog::logActivity('wallet_topup_failed', "Başarısız Cüzdan Yükleme Denemesi", ['error' => $message], $userId);
                }
            }
        }

        // CREATE INVISIBLE BRIDGE
        $magicUrl = URL::temporarySignedRoute(
            'wallet.magic-login',
            now()->addMinutes(10),
            [
                'user_id' => $userId,
                'status' => $status,
                'message' => $message
            ]
        );

        return redirect($magicUrl);
    }

    public function magicLogin(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Güvenlik doğrulaması başarısız.');
        }

        $userId = $request->query('user_id');
        if ($userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                Auth::login($user, true); // Force session restoration
                session()->regenerate();
            }
        }

        $status = $request->query('status') === 'success' ? 'success' : 'error';
        $message = $request->query('message', 'İşlem tamamlandı.');

        return redirect()->route('profile.wallet.index')->with($status, $message);
    }
}
