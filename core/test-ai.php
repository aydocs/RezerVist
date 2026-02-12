<?php

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'] ?? null;

if (! $apiKey) {
    exit("Error: GEMINI_API_KEY not found in .env\n");
}

$url = 'https://generativelanguage.googleapis.com/v1beta/models?key='.$apiKey;

echo "Fetching available models...\n";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($status === 200) {
    $data = json_decode($response, true);
    echo 'Found '.count($data['models'] ?? [])." models:\n";
    foreach ($data['models'] ?? [] as $model) {
        if (strpos($model['name'], 'gemini') !== false) {
            echo ' - '.$model['name'].' ('.implode(', ', $model['supportedGenerationMethods']).")\n";
        }
    }
} else {
    echo "FAILED to fetch models ($status)\n";
    echo $response."\n";
}

echo "\nDone.\n";
