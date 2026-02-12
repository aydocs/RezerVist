<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

class PaymentController extends Controller
{
    private function getOptions()
    {
        $options = new Options();
        $options->setApiKey(config('services.iyzico.api_key'));
        $options->setSecretKey(config('services.iyzico.secret_key'));
        $options->setBaseUrl(config('services.iyzico.base_url', 'https://sandbox-api.iyzipay.com'));
        return $options;
    }

    public function checkout(Request $request, $id)
    {
        \Log::info('PAYMENT CHECKOUT: Request received for reservation ' . $id);
        
        // Validate Signature for Public Access (WebView Fix)
        if (!$request->hasValidSignature()) {
            // Emulator Fix: Check if replacing 10.0.2.2 with 127.0.0.1 makes it valid
            // Android Emulator uses 10.0.2.2 to access host, but signature is generated for 127.0.0.1
            $currentUrl = $request->fullUrl();
            if (str_contains($currentUrl, '10.0.2.2')) {
                $originalUrl = str_replace('10.0.2.2', '127.0.0.1', $currentUrl);
                $tempRequest = \Illuminate\Http\Request::create($originalUrl);
                
                if (!$tempRequest->hasValidSignature()) {
                     \Log::warning('PAYMENT CHECKOUT: Invalid signature (even after emulator fix).');
                     abort(403, 'Invalid signature.');
                }
            } else {
                \Log::warning('PAYMENT CHECKOUT: Invalid signature.');
                abort(403, 'Invalid signature.');
            }
        }

        $reservation = Reservation::findOrFail($id);
        
        // Auto-login for WebView session
        if (!Auth::check() || Auth::id() !== $reservation->user_id) {
            \Log::info('PAYMENT CHECKOUT: Auto-logging in user ' . $reservation->user_id);
            Auth::loginUsingId($reservation->user_id);
        }

        $user = Auth::user();

        // Price calculation & Rounding
        $rawPrice = $reservation->total_amount > 0 ? $reservation->total_amount : ($reservation->price > 0 ? $reservation->price : 100.00); 
        $price = ceil($rawPrice); // Round to nearest integer (ceil) as requested
        
        $request = new CreateCheckoutFormInitializeRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($reservation->id);
        $request->setPrice($price);
        $request->setPaidPrice($price);
        $request->setCurrency(Currency::TL);
        $request->setBasketId("B" . $reservation->id);
        $request->setPaymentGroup(PaymentGroup::PRODUCT);
        $request->setCallbackUrl(route('payment.callback', ['reservation_id' => $reservation->id]));
        $request->setEnabledInstallments(array(2, 3, 6, 9));

        $buyer = new Buyer();
        $buyer->setId($user->id);
        $buyer->setName($user->name);
        $buyer->setSurname($user->name); 
        $buyer->setGsmNumber($user->phone ?? '+905555555555');
        $buyer->setEmail($user->email);
        $buyer->setIdentityNumber('11111111111');
        $buyer->setLastLoginDate(now()->format('Y-m-d H:i:s'));
        $buyer->setRegistrationDate($user->created_at->format('Y-m-d H:i:s'));
        $buyer->setRegistrationAddress($user->address ?? 'Istanbul');
        $buyer->setIp(request()->ip());
        $buyer->setCity($user->city ?? 'Istanbul');
        $buyer->setCountry($user->country ?? 'Turkey');
        $buyer->setZipCode($user->zip_code ?? '34000');
        $request->setBuyer($buyer);

        $shippingAddress = new Address();
        $shippingAddress->setContactName($user->name);
        $shippingAddress->setCity($user->city ?? 'Istanbul');
        $shippingAddress->setCountry($user->country ?? 'Turkey');
        $shippingAddress->setAddress($user->address ?? 'Istanbul');
        $shippingAddress->setZipCode($user->zip_code ?? '34000');
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new Address();
        $billingAddress->setContactName($user->name);
        $billingAddress->setCity($user->city ?? 'Istanbul');
        $billingAddress->setCountry($user->country ?? 'Turkey');
        $billingAddress->setAddress($user->address ?? 'Istanbul');
        $billingAddress->setZipCode($user->zip_code ?? '34000');
        $request->setBillingAddress($billingAddress);

        $basketItems = array();
        // Helper to add item to basket
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId("BI" . $reservation->id);
        $firstBasketItem->setName("Rezervasyon #" . $reservation->id);
        $firstBasketItem->setCategory1("Hizmet");
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $firstBasketItem->setPrice($price);

        // MARKETPLACE LOGIC
        // Only add subMerchantKey and subMerchantPrice if the business is a registered sub-merchant.
        if (!empty($reservation->business->iyzico_submerchant_key)) {
             $firstBasketItem->setSubMerchantKey($reservation->business->iyzico_submerchant_key);
             // Calculate sub-merchant price (post-commission)
             $commissionRate = $reservation->business->commission_rate ?? 0;
             $subMerchantPrice = $price - ($price * ($commissionRate / 100));
             $firstBasketItem->setSubMerchantPrice($subMerchantPrice);
        }
        
        $basketItems[0] = $firstBasketItem;
        $request->setBasketItems($basketItems);

        $checkoutFormInitialize = CheckoutFormInitialize::create($request, $this->getOptions());

        \Illuminate\Support\Facades\Log::info('Iyzico Checkout Init: ' . $checkoutFormInitialize->getStatus() . ' - ' . $checkoutFormInitialize->getErrorMessage());

        if ($checkoutFormInitialize->getStatus() == 'success') {
            \App\Models\ActivityLog::logActivity('payment_initiated', "Ödeme işlemi başlatıldı: {$price} TL", [
                'reservation_id' => $reservation->id,
                'price' => $price
            ], $user->id);

            $checkoutContent = $checkoutFormInitialize->getCheckoutFormContent();

            return response()->make('
                <html>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <title>Ödeme</title>
                    <style>
                        body { margin: 0; padding: 16px; font-family: -apple-system, sans-serif; background: #f8fafc; }
                        #iyzipay-checkout-form { min-height: 400px; }
                    </style>
                </head>
                <body>
                    <div id="iyzipay-checkout-form" class="responsive">' . $checkoutContent . '</div>
                </body>
                </html>
            ', 200, ['Content-Type' => 'text/html']);
        } else {
            \App\Models\ActivityLog::logActivity('payment_init_failed', "Ödeme başlatma hatası: " . $checkoutFormInitialize->getErrorMessage(), [
                'reservation_id' => $reservation->id
            ], $user->id);

            return response()->make('
                <html>
                <head><meta name="viewport" content="width=device-width, initial-scale=1">
                <style>
                    body { font-family: -apple-system, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background: #f8fafc; }
                    .card { text-align: center; padding: 32px; max-width: 400px; }
                    .icon { font-size: 48px; margin-bottom: 16px; }
                    h2 { color: #1e293b; margin-bottom: 8px; }
                    p { color: #64748b; line-height: 1.5; }
                </style>
                </head>
                <body>
                    <div class="card">
                        <div class="icon">⚠️</div>
                        <h2>Ödeme Başlatılamadı</h2>
                        <p>' . e($checkoutFormInitialize->getErrorMessage()) . '</p>
                    </div>
                </body>
                </html>
            ', 200, ['Content-Type' => 'text/html']);
        }
    }

    public function callback(Request $request)
    {
        $reservationId = $request->query('reservation_id');
        $token = $request->input('token'); 

        $reservation = Reservation::findOrFail($reservationId);
        
        // Retrieve result from Iyzico
        $authOptions = $this->getOptions();
        $iyzicoRequest = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $iyzicoRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $iyzicoRequest->setToken($token);
        
        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($iyzicoRequest, $authOptions);

        if ($checkoutForm->getStatus() == 'success' && $checkoutForm->getPaymentStatus() == 'SUCCESS') {
            try {
                // DB TRANSACTION: Update Reservation AND Save Payment
                $payment = DB::transaction(function() use ($reservation, $checkoutForm, $token) {
                    // 1. Update Reservation Status
                    $targetStatus = $reservation->guest_count > 8 ? 'pending' : 'approved';
                    
                    $reservation->status = $targetStatus;
                    $reservation->update(['status' => $targetStatus]);

                    // 2. Create Payment Record
                    $payment = Payment::create([
                        'user_id' => $reservation->user_id,
                        'reservation_id' => $reservation->id,
                        'payment_id' => $checkoutForm->getPaymentId(),
                        'conversation_id' => $checkoutForm->getConversationId(),
                        'price' => $checkoutForm->getPrice(),
                        'paid_price' => $checkoutForm->getPaidPrice(),
                        'status' => 'success',
                        'currency' => $checkoutForm->getCurrency(),
                        'basket_id' => $checkoutForm->getBasketId(),
                        'card_family' => $checkoutForm->getCardFamily(),
                        'card_type' => $checkoutForm->getCardType(),
                        'bin_number' => $checkoutForm->getBinNumber(),
                        'raw_result' => json_encode($checkoutForm->getRawResult()),
                    ]);

                    // 3. Credit Business Owner Wallet (For Display/Tracking)
                    // NOTE: Iyzico Marketplace handles actual settlement.
                    // We ALWAYS credit the wallet for internal tracking purposes.
                    $business = $reservation->business;
                    $totalPrice = $checkoutForm->getPaidPrice();
                    $commissionRate = $business->commission_rate ?? 5; // 5% platform default
                    $commissionAmount = ($totalPrice * $commissionRate) / 100;
                    $netEarning = $totalPrice - $commissionAmount;

                    $owner = $business->owner;
                    if ($owner) {
                        $owner->increment('balance', $netEarning);
                        
                        \App\Models\WalletTransaction::create([
                            'user_id' => $owner->id,
                            'amount' => $netEarning,
                            'type' => 'earning',
                            'status' => 'success',
                            'description' => "Rezervasyon Geliri (#{$reservation->id})",
                            'reference_id' => $reservation->id,
                            'meta_data' => [
                                'gross' => $totalPrice,
                                'commission' => $commissionAmount,
                                'business_id' => $business->id,
                                'iyzico_settled' => (bool) $business->iyzico_submerchant_key,
                            ],
                        ]);
                    }

                    return $payment;
                });

                // Log Payment Success
                \App\Models\ActivityLog::logPayment($payment, 'success');
                
                // Log Reservation Confirmation (New standard flow)
                \App\Models\ActivityLog::logReservation($reservation, 'confirmed');

                // Send confirmation email (Now moved here for card payments)
                try {
                    \Illuminate\Support\Facades\Mail::to($reservation->user->email)
                        ->send(new \App\Mail\ReservationConfirmed($reservation));
                } catch (\Exception $e) {
                    \Log::error('Failed to send confirmation email in callback: ' . $e->getMessage());
                }

                // Send In-App Notification (Approved)
                try {
                    $reservation->user->notify(new \App\Notifications\ReservationStatusNotification($reservation, 'approved'));
                } catch (\Exception $e) {
                    \Log::error('Failed to send approval notification: ' . $e->getMessage());
                }

                $bridgeUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'payment.magic-login',
                    now()->addMinutes(15),
                    [
                        'user_id' => $reservation->user_id,
                        'reservation_id' => $reservation->id,
                        'status' => 'success',
                        'token' => hash_hmac('sha256', 'confirmation_' . $reservation->id, config('app.key'))
                    ]
                );

                return redirect($bridgeUrl);

            } catch (\Exception $e) {
                \Log::error('Payment processing error: ' . $e->getMessage());
                return redirect('/')->with('error', 'Ödeme kaydedilirken hata oluştu: ' . $e->getMessage());
            }
        } else {
             // Log failed attempt if needed
             // We can optionally save failed attempts too, but minimal requirement is successful ones.
             // If we want logging for All, we should save here too.
             // Master Rule says "Her işlem DB'ye yazılacak". So we should save failure.
             try {
                 $failedPayment = Payment::create([
                    'user_id' => $reservation->user_id,
                    'reservation_id' => $reservation->id,
                    'status' => 'failed',
                    'price' => $reservation->price > 0 ? $reservation->price : 100, 
                    'paid_price' => 0,
                    'raw_result' => json_encode($checkoutForm->getRawResult() ?? ['error' => $checkoutForm->getErrorMessage()]),
                ]);

                \App\Models\ActivityLog::logPayment($failedPayment, 'failed');

             } catch(\Exception $e) {
                 \Log::error('Failed to save failed payment log: ' . $e->getMessage());
             }

            return redirect('/')->with('error', 'Ödeme başarısız veya iptal edildi.');
        }
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
                Auth::login($user, true); // Restore session
                session()->regenerate();
            }
        }

        $reservationId = $request->query('reservation_id');
        $status = $request->query('status');
        $token = $request->query('token');

        if ($status === 'success') {
            return redirect()->route('booking.confirmation', ['id' => $reservationId, 'token' => $token])
                ->with('success', 'Ödeme başarılı! Rezervasyonunuz onaylandı.');
        }

        return redirect('/')->with('error', 'İşlem sırasında bir sorun oluştu.');
    }
}
