<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Services\SettingService;
use Illuminate\Support\Facades\Config;

echo "Iyzico Configuration Diagnosis\n";
echo "-------------------------------\n";

$apiKey = Config::get('services.iyzico.api_key');
$secretKey = Config::get('services.iyzico.secret_key');
$baseUrl = Config::get('services.iyzico.base_url');

echo "API Key: " . ($apiKey ?: 'MISSING') . "\n";
echo "Secret Key: " . ($secretKey ? 'PRESENT (hidden)' : 'MISSING') . "\n";
echo "Base URL: " . ($baseUrl ?: 'MISSING') . "\n";

echo "\nChecking Database Table 'settings' for iyzico group:\n";
try {
    $settings = \App\Models\Setting::where('group', 'iyzico')->get();
    if ($settings->isEmpty()) {
        echo "No settings found for group 'iyzico' in database.\n";
    } else {
        foreach ($settings as $s) {
            echo "- {$s->key}: {$s->value}\n";
        }
    }
} catch (\Exception $e) {
    echo "Error querying database: " . $e->getMessage() . "\n";
}

echo "\nTesting Iyzico SDK Initialization:\n";
try {
    $options = new \Iyzipay\Options();
    $options->setApiKey($apiKey);
    $options->setSecretKey($secretKey);
    $options->setBaseUrl($baseUrl);
    
    // Attempt a simple request
    $request = new \Iyzipay\Request\RetrieveBinNumberRequest();
    $request->setLocale(\Iyzipay\Model\Locale::TR);
    $request->setConversationId("diag_" . time());
    $request->setBinNumber("454671"); // Sample BIN
    
    $binNumber = \Iyzipay\Model\BinNumber::retrieve($request, $options);
    
    echo "Status: " . $binNumber->getStatus() . "\n";
    if ($binNumber->getStatus() !== 'success') {
        echo "Error Code: " . $binNumber->getErrorCode() . "\n";
        echo "Error Message: " . $binNumber->getErrorMessage() . "\n";
    } else {
        echo "SDK connection test successful!\n";
    }
} catch (\Exception $e) {
    echo "Exception during SDK test: " . $e->getMessage() . "\n";
}
