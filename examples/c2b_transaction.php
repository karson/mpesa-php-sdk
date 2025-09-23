<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'config.php';

use Karson\MpesaPhpSdk\Mpesa;


echo "=== C2B Transaction Example ===\n\n";

$mpesa = new Mpesa(
    publicKey: $publicKey,
    apiKey: $apiKey,
    isTest: $isTest,
    serviceProviderCode: $serviceProviderCode
);

// C2B Sync Transaction - Retorna BaseResponse
echo "1. C2B Sync Transaction:\n";
$syncResponse = $mpesa->c2b(
    transactionReference: 'C2BTXN001' . time(),
    customerMSISDN: '258841234567',
    amount: 100,
    thirdPartReference: 'REF001'
);

if ($syncResponse->isTransactionSuccessful()) {
    echo "✓ Transaction successful!\n";
    echo "Transaction ID: " . $syncResponse->getTransactionId() . "\n";
    echo "Conversation ID: " . $syncResponse->getConversationId() . "\n";
    echo "Response Code: " . $syncResponse->getResponseCode() . "\n";
} else {
    echo "✗ Transaction failed\n";
    if (!$syncResponse->isSuccessful()) {
        echo "HTTP Error: " . $syncResponse->getStatusCode() . "\n";
        echo "code: " . $syncResponse->getResponseCode() . "\n";
        echo "description: " . $syncResponse->getResponseDescription() . "\n";
    }
    if (!$syncResponse->isApiSuccess()) {
        echo "API Error: " . $syncResponse->getApiResponseDescription() . "\n";
    }
}

