<?php

namespace App\Services;

use App\Models\Business;
use Iyzipay\Model\Locale;
use Iyzipay\Model\SubMerchant;
use Iyzipay\Options;
use Iyzipay\Request\CreateSubMerchantRequest;

class IyzicoMarketplaceService
{
    private function getOptions()
    {
        $options = new Options;
        $options->setApiKey(config('services.iyzico.api_key'));
        $options->setSecretKey(config('services.iyzico.secret_key'));
        $options->setBaseUrl(config('services.iyzico.base_url', 'https://sandbox-api.iyzipay.com'));

        return $options;
    }

    /**
     * Create a sub-merchant in Iyzico
     */
    public function registerSubMerchant(Business $business, array $data)
    {
        $request = new CreateSubMerchantRequest;
        $request->setLocale(Locale::TR);
        $request->setConversationId((string) $business->id);
        $request->setSubMerchantExternalId('B'.$business->id);

        $request->setSubMerchantType($data['submerchant_type']);
        $request->setAddress($business->address ?? 'Istanbul');
        // Split name into name and surname
        $fullName = $business->owner->name;
        $nameParts = explode(' ', $fullName);
        $surname = array_pop($nameParts);
        $name = implode(' ', $nameParts) ?: $surname; // Fallback if no space

        $request->setContactName($name);
        $request->setContactSurname($surname);
        $request->setEmail($business->owner->email);
        $request->setGsmNumber($business->phone ?? '+905555555555');
        $request->setName($data['legal_company_name'] ?? $business->name);
        $request->setIban(preg_replace('/[^A-Z0-9]/', '', $data['iyzico_iban']));

        if ($data['submerchant_type'] === 'PERSONAL') {
            $request->setIdentityNumber($data['identity_number'] ?? '11111111111');
        } else {
            $request->setTaxOffice($data['tax_office'] ?? 'Istanbul');
            $request->setTaxNumber($data['tax_number'] ?? '1234567890');
        }

        $request->setCurrency('TRY');

        $subMerchant = SubMerchant::create($request, $this->getOptions());

        if ($subMerchant->getStatus() == 'success') {
            $business->update([
                'iyzico_submerchant_key' => $subMerchant->getSubMerchantKey(),
                'submerchant_type' => $data['submerchant_type'],
                'tax_office' => $data['tax_office'] ?? null,
                'tax_number' => $data['tax_number'] ?? null,
                'legal_company_name' => $data['legal_company_name'] ?? $business->name,
                'iyzico_iban' => $data['iyzico_iban'],
            ]);

            return ['status' => 'success', 'key' => $subMerchant->getSubMerchantKey()];
        }

        return ['status' => 'error', 'message' => $subMerchant->getErrorMessage()];
    }
}
