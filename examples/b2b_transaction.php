<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Karson\MpesaPhpSdk\Mpesa;

echo "=== B2B Transaction Example ===\n\n";

$mpesa = new Mpesa(
    publicKey: 'your_public_key_here',
    apiKey: 'your_api_key_here',
    isTest: true, // true for sandbox, false for production
    providerCode: '171717'
);

// B2B Sync Transaction
echo "1. B2B Sync Transaction:\n";
$response = $mpesa->b2b(
    transactionReference: 'B2B_TXN_001',
    amount: 300,
    thirdPartReference: 'B2B_REF_001',
    primaryPartyCode: '171717',
    receiverPartyCode: '979797'
);

if ($response->isTransactionSuccessful()) {
    echo "✓ B2B transaction successful!\n";
    echo "Transaction ID: " . $response->getTransactionId() . "\n";
    echo "Conversation ID: " . $response->getConversationId() . "\n";
    echo "Third Party Reference: " . $response->getThirdPartyReference() . "\n";
} else {
    echo "✗ B2B transaction failed\n";
}

// B2B Async Transaction
echo "\n2. B2B Async Transaction:\n";
$asyncResponse = $mpesa->b2b(
    transactionReference: 'B2B_ASYNC_001',
    amount: 500,
    thirdPartReference: 'B2B_ASYNC_REF_001',
    primaryPartyCode: '171717',
    receiverPartyCode: '979797',
    isAsync: true
);

if ($asyncResponse->isTransactionInitiated()) {
    echo "✓ Async B2B transaction initiated!\n";
    echo "Conversation ID: " . $asyncResponse->getConversationId() . "\n";
    echo "Third Party Reference: " . $asyncResponse->getThirdPartyReference() . "\n";
}

echo "\n=== B2B Transaction Example Completed ===\n";
