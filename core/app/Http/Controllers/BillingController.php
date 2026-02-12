<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class BillingController extends Controller
{
    protected $subscriptionService;

    public function __construct(\App\Services\SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        // Try both relationships: direct link OR owner link
        $business = $user->business ?? $user->ownedBusiness;

        if (!$business) {
            return redirect()->route('dashboard')->with('error', 'Bu sayfaya erişmek için önce bir işletme hesabı oluşturmalısınız veya işletme sahibi olmalısınız.');
        }

        $subscription = $business->activeSubscription;
        $packages = Package::where('is_active', true)->get();
        $invoices = $business->invoices()->latest()->get();

        return view('vendor.billing.index', compact('business', 'subscription', 'packages', 'invoices'));
    }

    public function checkout(Request $request, $packageId)
    {
        $package = Package::findOrFail($packageId);
        $user = $request->user();
        $business = $user->business ?? $user->ownedBusiness;
        $months = (int) $request->get('months', 1);

        // Instant free plan assignment
        if ($package->price_monthly == 0) {
            $this->subscriptionService->assignPlan($business, $package, 120, 'system');
            return redirect()->route('vendor.billing.index')->with('success', 'Ücretsiz paketiniz başarıyla tanımlandı.');
        }

        // Calculate Price and apply discounts
        $totalPrice = $package->price_monthly * $months;
        $discount = 0;
        if ($months == 3) $discount = 0.05;
        if ($months == 6) $discount = 0.10;
        if ($months == 12) $discount = 0.20;
        
        $finalPrice = $totalPrice * (1 - $discount);

        // Iyzico Configuration
        $options = new \Iyzipay\Options();
        $options->setApiKey(env('IYZICO_API_KEY', 'sandbox-shWyZW2KbsTTGXf0idvOEMIyNTgRcIfG'));
        $options->setSecretKey(env('IYZICO_SECRET_KEY', 'sandbox-ZukSyTsa6g300pf4JmZD6CZJHPLqlITJ'));
        $options->setBaseUrl(env('IYZICO_BASE_URL', 'https://sandbox-api.iyzipay.com'));

        // Request Object
        $iyzicoRequest = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $iyzicoRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $iyzicoRequest->setConversationId(uniqid());
        $iyzicoRequest->setPrice($finalPrice);
        $iyzicoRequest->setPaidPrice($finalPrice);
        $iyzicoRequest->setCurrency(\Iyzipay\Model\Currency::TL);
        $iyzicoRequest->setBasketId("B" . $business->id);
        $iyzicoRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::SUBSCRIPTION);
        $iyzicoRequest->setCallbackUrl(route('billing.callback', ['package_id' => $package->id, 'months' => $months]));

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($user->id);
        $buyer->setName($user->name);
        $buyer->setSurname($user->name); // Simplified
        $buyer->setGsmNumber($user->phone ?? '05551112233');
        $buyer->setEmail($user->email);
        $buyer->setIdentityNumber("11111111111");
        $buyer->setRegistrationAddress($business->address ?? 'Adres Belirtilmemiş');
        $buyer->setIp($request->ip());
        $buyer->setCity("Istanbul");
        $buyer->setCountry("Turkey");
        $iyzicoRequest->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($user->name);
        $shippingAddress->setCity("Istanbul");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress($business->address ?? 'Adres Belirtilmemiş');
        $iyzicoRequest->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($user->name);
        $billingAddress->setCity("Istanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress($business->address ?? 'Adres Belirtilmemiş');
        $iyzicoRequest->setBillingAddress($billingAddress);

        $basketItems = array();
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId("P" . $package->id);
        $firstBasketItem->setName($package->name . " ($months Ay)");
        $firstBasketItem->setCategory1("SaaS");
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $firstBasketItem->setPrice($finalPrice);
        $basketItems[0] = $firstBasketItem;
        $iyzicoRequest->setBasketItems($basketItems);

        // Form Initialize
        try {
            $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($iyzicoRequest, $options);
            if ($checkoutFormInitialize->getStatus() !== 'success') {
                return back()->with('error', 'Ödeme sistemi hatası: ' . $checkoutFormInitialize->getErrorMessage());
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Ödeme sistemi başlatılamadı kanka: ' . $e->getMessage());
        }

        return view('vendor.billing.iyzico', [
            'paymentContent' => $checkoutFormInitialize->getCheckoutFormContent()
        ]);
    }

    public function callback(Request $request)
    {
        $token = $request->get('token');
        $packageId = $request->get('package_id');
        $months = (int) $request->get('months', 1);
        $package = Package::findOrFail($packageId);
        
        $status = 'error';
        $message = 'Ödeme işlemi başarısız oldu veya iptal edildi.';
        $ownerId = null;

        // Verify with Iyzico
        $options = new \Iyzipay\Options();
        $options->setApiKey(env('IYZICO_API_KEY', 'sandbox-shWyZW2KbsTTGXf0idvOEMIyNTgRcIfG'));
        $options->setSecretKey(env('IYZICO_SECRET_KEY', 'sandbox-ZukSyTsa6g300pf4JmZD6CZJHPLqlITJ'));
        $options->setBaseUrl(env('IYZICO_BASE_URL', 'https://sandbox-api.iyzipay.com'));

        $iyzicoRequest = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $iyzicoRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $iyzicoRequest->setToken($token);

        try {
            $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($iyzicoRequest, $options);
            if ($checkoutForm->getPaymentStatus() === 'SUCCESS') {
                $basketId = $checkoutForm->getBasketId(); // Format: B123
                $businessId = substr($basketId, 1);
                $business = Business::find($businessId);
                
                if ($business) {
                    $this->subscriptionService->assignPlan($business, $package, $months, 'iyzico');
                    $status = 'success';
                    $message = "Aboneliğiniz başarıyla {$package->name} paketine ({$months} Ay) yükseltildi.";
                    $ownerId = $business->owner_id;
                }
            } else {
                $message = $checkoutForm->getErrorMessage() ?: 'Ödeme başarısız.';
            }
        } catch (\Exception $e) {
            $message = 'Ödeme onayı sırasında hata oluştu: ' . $e->getMessage();
        }

        // CREATE INVISIBLE BRIDGE (Standard procedure for cross-site POST session loss)
        $magicUrl = URL::temporarySignedRoute(
            'billing.magic-login',
            now()->addMinutes(15),
            [
                'user_id' => $ownerId,
                'status' => $status,
                'message' => $message
            ]
        );

        return redirect($magicUrl);
    }

    public function magicLogin(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Güvenlik doğrulaması başarısız veya bağlantı süresi dolmuş.');
        }

        $userId = $request->query('user_id');
        if ($userId) {
            $user = User::find($userId);
            if ($user && $user->role === 'business') {
                Auth::login($user, true);
                session()->regenerate();
            }
        }

        $status = $request->query('status') === 'success' ? 'success' : 'error';
        $message = $request->query('message');

        return redirect()->route('vendor.billing.index')->with($status, $message);
    }

    public function upgrade(Request $request, $packageId)
    {
        return $this->checkout($request, $packageId);
    }
}
