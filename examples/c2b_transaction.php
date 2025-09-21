<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Karson\MpesaPhpSdk\Mpesa;
use Karson\MpesaPhpSdk\Response\SyncResponse;
use Karson\MpesaPhpSdk\Response\AsyncResponse;

echo "=== C2B Transaction Example ===\n\n";

$mpesa = new Mpesa(
    publicKey: 'your_public_key_here',
    apiKey: 'your_api_key_here',
    isTest: true, // true for sandbox, false for production
    serviceProviderCode: '171717',
    isAsync: false
);

// C2B Sync Transaction - Retorna SyncResponse
echo "1. C2B Sync Transaction:\n";
$syncResponse = $mpesa->c2b(
    transactionReference: 'C2B_TXN_001',
    from: '258841234567',
    amount: 100,
    thirdPartReference: 'REF_001'
);

// Agora o IDE sabe que é SyncResponse e tem os métodos específicos
if ($syncResponse instanceof SyncResponse && $syncResponse->isTransactionSuccessful()) {
    echo "✓ Transaction successful!\n";
    echo "Transaction ID: " . $syncResponse->getTransactionId() . "\n";
    echo "Conversation ID: " . $syncResponse->getConversationId() . "\n";
    echo "Response Code: " . $syncResponse->getResponseCode() . "\n";
} else {
    echo "✗ Transaction failed\n";
    if (!$syncResponse->isSuccessful()) {
        echo "HTTP Error: " . $syncResponse->getStatusCode() . "\n";
    }
    if (!$syncResponse->isApiSuccess()) {
        echo "API Error: " . $syncResponse->getApiResponseDescription() . "\n";
    }
}

// C2B Async Transaction - Retorna AsyncResponse
echo "\n2. C2B Async Transaction:\n";
$asyncResponse = $mpesa->c2b(
    transactionReference: 'C2B_ASYNC_001',
    from: '258841234567',
    amount: 200,
    thirdPartReference: 'ASYNC_REF_001',
);

// Agora o IDE sabe que é AsyncResponse e tem os métodos específicos
if ($asyncResponse instanceof AsyncResponse && $asyncResponse->isTransactionInitiated()) {
    echo "✓ Async transaction initiated!\n";
    echo "Conversation ID: " . $asyncResponse->getConversationId() . "\n";
    echo "Third Party Reference: " . $asyncResponse->getThirdPartyReference() . "\n";
    echo "Response Code: " . $asyncResponse->getResponseCode() . "\n";
    
    if ($asyncResponse->isAcceptedForProcessing()) {
        echo "✓ Transaction accepted for processing\n";
    }
} else {
    echo "✗ Async transaction failed to initiate\n";
}

echo "\n=== C2B Transaction Example Completed ===\n";
