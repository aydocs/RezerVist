<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Order;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CustomerQrController extends Controller
{
    /**
     * Entry point: Scan QR -> Redirect to Menu
     * URL: /q/{hex_payload}
     */
    public function index($payload)
    {
        try {
            $encrypted = @hex2bin($payload);
            if ($encrypted === false) throw new \Exception('Invalid hex');

            $decrypted = Crypt::decryptString($encrypted);
            [$businessId, $resourceId] = explode('|', $decrypted);

            $business = Business::findOrFail($businessId);
            $resource = Resource::where('business_id', $businessId)->findOrFail($resourceId);

            session([
                'qr_business_id' => $businessId,
                'qr_resource_id' => $resourceId,
            ]);

            // return redirect()->route('qr.menu', ['payload' => $payload]);
            return view('qr.index', compact('business', 'resource', 'payload'));

        } catch (\Exception $e) {
            Log::error('QR Index Error: ' . $e->getMessage());
            return response()->view('errors.404', [], 404);
        }
    }

    /**
     * Show Digital Menu
     * URL: /m/{hex_payload}
     */
    public function menu($payload)
    {
        try {
            $encrypted = @hex2bin($payload);
            if ($encrypted === false) throw new \Exception('Invalid hex');

            $decrypted = Crypt::decryptString($encrypted);
            [$businessId, $resourceId] = explode('|', $decrypted);

            $business = Business::findOrFail($businessId);
            $resource = Resource::findOrFail($resourceId);

            // Fetch all active menu items
            $allItems = $business->menus()
                ->where('is_available', true)
                ->orderBy('category')
                ->get();

            // Group by Category
            // Result: ['Starters' => [Item1, Item2], 'Mains' => [Item3]]
            $groupedMenus = $allItems->groupBy('category');

            return view('qr.menu', compact('business', 'resource', 'groupedMenus', 'payload'));

        } catch (\Exception $e) {
            Log::error('QR Menu Error: ' . $e->getMessage());
            return response()->view('errors.404', [], 404);
        }
    }

    /**
     * Show Bill / Adisyon
     * URL: /b/{hex_payload}
     */
    public function bill($payload)
    {
        try {
            $encrypted = @hex2bin($payload);
            if ($encrypted === false) throw new \Exception('Invalid hex');

            $decrypted = Crypt::decryptString($encrypted);
            [$businessId, $resourceId] = explode('|', $decrypted);

            $business = Business::findOrFail($businessId);
            $resource = Resource::findOrFail($resourceId);

            $order = Order::where('business_id', $businessId)
                ->where('resource_id', $resourceId)
                ->where('status', 'active')
                ->with('items')
                ->latest('id')
                ->first();

            return view('qr.bill', compact('business', 'resource', 'order', 'payload'));

        } catch (\Exception $e) {
            Log::error('QR Bill Error: ' . $e->getMessage());
            abort(404);
        }
    }

    /**
     * Initiate Payment for Public QR Order
     * POST /q/pay/{hex_payload}
     */
    public function initiatePayment(Request $request, $payload)
    {
        try {
            $encrypted = @hex2bin($payload);
            if ($encrypted === false) throw new \Exception('Invalid hex');

            $decrypted = Crypt::decryptString($encrypted);
            [$businessId, $resourceId] = explode('|', $decrypted);

            $business = Business::findOrFail($businessId);

            // Check if QR payments are enabled for this business
            if (!$business->isQrPaymentsEnabled()) {
                return redirect()->back()->with('error', 'Bu işletme şu anda QR menü üzerinden ödeme kabul etmemektedir. Lütfen garsona danışın.');
            }

            $order = Order::where('business_id', $businessId)
                ->where('resource_id', $resourceId)
                ->where('status', 'active')
                ->latest('id')
                ->firstOrFail();

            $price = $order->total_amount - $order->paid_amount;
            
            // Ensure user is authenticated before payment
            if (!auth()->check()) {
                session(['url.intended' => route('qr.bill', ['payload' => $payload])]);
                return redirect()->route('login')->with('info', 'Ödeme yapabilmek için lütfen giriş yapın veya kayıt olun.');
            }

            $user = auth()->user();

            // --- Iyzico Logic ---
            $options = new \Iyzipay\Options();
            $options->setApiKey(config('services.iyzico.api_key'));
            $options->setSecretKey(config('services.iyzico.secret_key'));
            $options->setBaseUrl(config('services.iyzico.base_url', 'https://sandbox-api.iyzipay.com'));

            $requestIyzico = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
            $requestIyzico->setLocale(\Iyzipay\Model\Locale::TR);
            $requestIyzico->setConversationId($order->id); 
            $requestIyzico->setPrice($price);
            $requestIyzico->setPaidPrice($price);
            $requestIyzico->setCurrency(\Iyzipay\Model\Currency::TL);
            $requestIyzico->setBasketId('ORD' . $order->id);
            $requestIyzico->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
            $requestIyzico->setCallbackUrl(route('qr.payment.callback', ['payload' => $payload]));
            $requestIyzico->setEnabledInstallments([1]);

            // Buyer Info from Authenticated User
            $buyer = new \Iyzipay\Model\Buyer();
            $buyer->setId($user->id);
            $buyer->setName($user->name ?: 'Müşteri');
            $buyer->setSurname($user->surname ?: 'RezerVist');
            $buyer->setGsmNumber($user->phone ?: '+905555555555');
            $buyer->setEmail($user->email);
            $buyer->setIdentityNumber('11111111111');
            $buyer->setLastLoginDate(now()->format('Y-m-d H:i:s'));
            $buyer->setRegistrationDate($user->created_at->format('Y-m-d H:i:s'));
            $buyer->setRegistrationAddress('N/A');
            $buyer->setIp(request()->ip());
            $buyer->setCity('Istanbul');
            $buyer->setCountry('Turkey');
            $buyer->setZipCode('34000');
            $requestIyzico->setBuyer($buyer);

            $billingAddress = new \Iyzipay\Model\Address();
            $billingAddress->setContactName('Misafir');
            $billingAddress->setCity('Istanbul');
            $billingAddress->setCountry('Turkey');
            $billingAddress->setAddress('N/A');
            $billingAddress->setZipCode('34000');
            $requestIyzico->setBillingAddress($billingAddress);
            $requestIyzico->setShippingAddress($billingAddress);

            $basketItems = [];
            $item = new \Iyzipay\Model\BasketItem();
            $item->setId('BI' . $order->id);
            $item->setName('Adisyon #' . $order->id);
            $item->setCategory1('Yiyecek');
            $item->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
            $item->setPrice($price);
            
            // For Marketplace accounts, subMerchantKey is required for each item
            if ($business->iyzico_submerchant_key) {
                $item->setSubMerchantKey($business->iyzico_submerchant_key);
            }
            
            $basketItems[] = $item;
            $requestIyzico->setBasketItems($basketItems);

            $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($requestIyzico, $options);

            if ($checkoutFormInitialize->getStatus() == 'success') {
                $checkoutFormContent = $checkoutFormInitialize->getCheckoutFormContent();
                return view('qr.checkout', compact('business', 'resource', 'checkoutFormContent', 'payload'));
            } else {
                return redirect()->back()->with('error', 'Ödeme sistemi hatası: ' . $checkoutFormInitialize->getErrorMessage());
            }

        } catch (\Exception $e) {
            Log::error('QR Payment Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Bir hata oluştu.');
        }
    }

    public function paymentCallback(Request $request)
    {
        // Simple callback handling to update order status
        $token = $request->input('token');
        $payload = $request->input('payload'); // From route param likely? No, callback comes from Iyzico POST directly usually

        // If Iyzico posts back, we need to handle it.
        // Actually, Iyzico redirects user BROWSER to callbackUrl with token parameter.
        // So we can extract payload from URL if we included it in callbackUrl.
        // We defined callbackUrl as: route('qr.payment.callback', ['payload' => $payload])
        
        // Retrieve result
        $options = new \Iyzipay\Options();
        $options->setApiKey(config('services.iyzico.api_key'));
        $options->setSecretKey(config('services.iyzico.secret_key'));
        $options->setBaseUrl(config('services.iyzico.base_url', 'https://sandbox-api.iyzipay.com'));

        $requestIyzico = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $requestIyzico->setLocale(\Iyzipay\Model\Locale::TR);
        $requestIyzico->setToken($token);

        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($requestIyzico, $options);

        if ($checkoutForm->getStatus() == 'success' && $checkoutForm->getPaymentStatus() == 'SUCCESS') {
           
            $orderId = $checkoutForm->getConversationId();
            $paidPrice = $checkoutForm->getPaidPrice();

            $order = Order::find($orderId);
            if ($order) {
                $order->increment('paid_amount', $paidPrice);
                
                // Mark all order items as completed
                foreach ($order->items as $item) {
                    $item->update(['status' => 'completed']);
                }

                // Update payment status and method, keep order status active
                $order->update([
                    'payment_status' => 'paid',
                    'payment_method' => 'iyzico_app',
                    // 'status' is kept as 'active' as per instruction
                ]);
            }
            
            // Redirect back to bill page
            // We need payload to construct route. It should be in request route params if we set it up in web.php
            $payload = $request->route('payload'); 
            
            return redirect()->route('qr.bill', ['payload' => $payload])->with('success', 'Ödeme Başarılı!');
        }

        return redirect()->route('qr.bill', ['payload' => $request->route('payload')])->with('error', 'Ödeme Tamamlanamadı.');
    }

    public static function generateLink($businessId, $resourceId)
    {
        $encrypted = Crypt::encryptString("{$businessId}|{$resourceId}");
        $payload = bin2hex($encrypted);
        return route('qr.index', ['payload' => $payload]);
    }
}
