<?php

namespace App\Services;

use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Currency;
use Iyzipay\Model\Locale;
use Iyzipay\Model\Payment;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\PaymentChannel;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Options;
use Illuminate\Support\Facades\Log;

class IyzicoPaymentService
{
    private function getOptions()
    {
        $options = new Options();
        $options->setApiKey(config('services.iyzico.api_key'));
        $options->setSecretKey(config('services.iyzico.secret_key'));
        $options->setBaseUrl(config('services.iyzico.base_url', 'https://sandbox-api.iyzipay.com'));
        return $options;
    }

    public function pay(array $data)
    {
        $request = new \Iyzipay\Request\CreatePaymentRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($data['conversation_id']);
        $request->setPrice($data['price']);
        $request->setPaidPrice($data['paid_price']);
        $request->setCurrency(Currency::TL);
        $request->setInstallment(1);
        $request->setBasketId($data['basket_id']);
        $request->setPaymentChannel(PaymentChannel::WEB);
        $request->setPaymentGroup(PaymentGroup::PRODUCT);

        $paymentCard = new PaymentCard();
        $paymentCard->setCardHolderName($data['card']['holder']);
        $paymentCard->setCardNumber(str_replace(' ', '', $data['card']['number']));
        $paymentCard->setExpireMonth(explode('/', $data['card']['expiry'])[0]);
        $paymentCard->setExpireYear('20' . explode('/', $data['card']['expiry'])[1]);
        $paymentCard->setCvc($data['card']['cvc']);
        $paymentCard->setRegisterCard($data['register_card'] ?? 0);
        $request->setPaymentCard($paymentCard);

        $buyer = new Buyer();
        $buyer->setId($data['user']->id ?? 'guest');
        $buyer->setName($data['user']->name ?? 'Misafir');
        $buyer->setSurname($data['user']->surname ?? 'Kullanıcı');
        $buyer->setGsmNumber($data['user']->phone ?? '+905555555555');
        $buyer->setEmail($data['user']->email ?? 'guest@rezervist.com');
        $buyer->setIdentityNumber('11111111111');
        $buyer->setLastLoginDate(date('Y-m-d H:i:s'));
        $buyer->setRegistrationDate(date('Y-m-d H:i:s'));
        $buyer->setRegistrationAddress("N/A");
        $buyer->setIp($data['ip']);
        $buyer->setCity("Istanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("34732");
        $request->setBuyer($buyer);

        $billingAddress = new Address();
        $billingAddress->setContactName($data['user']->name ?? 'Misafir');
        $billingAddress->setCity("Istanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress("N/A");
        $billingAddress->setZipCode("34732");
        $request->setBillingAddress($billingAddress);
        $request->setShippingAddress($billingAddress);

        $basketItems = [];
        $item = new BasketItem();
        $item->setId($data['basket_id']);
        $item->setName("Rezervasyon #" . $data['basket_id']);
        $item->setCategory1("Hizmet");
        $item->setItemType(BasketItemType::VIRTUAL);
        $item->setPrice($data['price']);
        
        if (!empty($data['subMerchantKey'])) {
            $item->setSubMerchantKey($data['subMerchantKey']);
            $item->setSubMerchantPrice($data['paid_price']);
        }

        $basketItems[] = $item;
        $request->setBasketItems($basketItems);

        $payment = Payment::create($request, $this->getOptions());

        if ($payment->getStatus() == 'success') {
            return [
                'status' => 'success', 
                'paymentId' => $payment->getPaymentId(), 
                'cardUserKey' => $payment->getCardUserKey(),
                'cardToken' => $payment->getCardToken(),
                'raw' => $payment
            ];
        } else {
            Log::error('Iyzico Payment Failed: ' . $payment->getErrorMessage());
            return ['status' => 'error', 'message' => $payment->getErrorMessage(), 'raw' => $payment];
        }
    }
    public function createCheckoutForm(array $data)
    {
        $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($data['conversation_id']);
        $request->setPrice($data['price']);
        $request->setPaidPrice($data['paid_price']);
        $request->setCurrency(Currency::TL);
        $request->setBasketId($data['basket_id']);
        $request->setPaymentGroup(PaymentGroup::PRODUCT);
        $request->setCallbackUrl($data['callback_url']);
        $request->setEnabledInstallments([1]);

        $buyer = new Buyer();
        $buyer->setId($data['user']->id ?? 'guest');
        $buyer->setName($data['user']->name ?? 'Misafir');
        $buyer->setSurname($data['user']->surname ?? 'Kullanıcı');
        $buyer->setGsmNumber($data['user']->phone ?? '+905555555555');
        $buyer->setEmail($data['user']->email ?? 'guest@rezervist.com');
        $buyer->setIdentityNumber('11111111111');
        $buyer->setLastLoginDate(date('Y-m-d H:i:s'));
        $buyer->setRegistrationDate(date('Y-m-d H:i:s'));
        $buyer->setRegistrationAddress("N/A");
        $buyer->setIp($data['ip']);
        $buyer->setCity("Istanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("34732");
        $request->setBuyer($buyer);

        $billingAddress = new Address();
        $billingAddress->setContactName($data['user']->name ?? 'Misafir');
        $billingAddress->setCity("Istanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress("N/A");
        $billingAddress->setZipCode("34732");
        $request->setBillingAddress($billingAddress);
        $request->setShippingAddress($billingAddress);

        $basketItems = [];
        $item = new BasketItem();
        $item->setId($data['basket_id']);
        $item->setName("Rezervasyon #" . $data['basket_id']);
        $item->setCategory1("Hizmet");
        $item->setItemType(BasketItemType::VIRTUAL);
        $item->setPrice($data['price']);
        
        if (!empty($data['subMerchantKey'])) {
            $item->setSubMerchantKey($data['subMerchantKey']);
            $item->setSubMerchantPrice($data['paid_price']);
        }

        $basketItems[] = $item;
        $request->setBasketItems($basketItems);

        $checkoutForm = \Iyzipay\Model\CheckoutFormInitialize::create($request, $this->getOptions());

        if ($checkoutForm->getStatus() == 'success') {
            return ['status' => 'success', 'url' => $checkoutForm->getPaymentPageUrl(), 'token' => $checkoutForm->getToken()];
        } else {
            Log::error('Iyzico Checkout Form Init Failed: ' . $checkoutForm->getErrorMessage());
            return ['status' => 'error', 'message' => $checkoutForm->getErrorMessage()];
        }
    }

    public function retrieveCheckoutForm($token, $subMerchantKey = null)
    {
        $request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId("retrieve_" . time());
        $request->setToken($token);

        $options = $this->getOptions();
        
        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, $options);

        if ($checkoutForm->getStatus() == 'success') {
             if ($checkoutForm->getPaymentStatus() == 'SUCCESS') {
                 return ['status' => 'success', 'paymentId' => $checkoutForm->getPaymentId()];
             } else {
                 return ['status' => 'error', 'message' => 'Ödeme alınamadı. Banka reddetti.'];
             }
        } else {
            return ['status' => 'error', 'message' => $checkoutForm->getErrorMessage()];
        }
    }
}
