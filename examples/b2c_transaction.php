<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Karson\MpesaPhpSdk\Mpesa;

echo "=== B2C Transaction Example ===\n\n";

$mpesa = new Mpesa(
    publicKey: 'your_public_key_here',
    apiKey: 'your_api_key_here',
    isTest: true, // true for sandbox, false for production
    providerCode: '171717'
);

// B2C Sync Transaction
echo "1. B2C Sync Transaction:\n";
$response = $mpesa->send(
    transactionReference: 'B2C_TXN_001',
    to: '258841234567',
    amount: 50,
    thirdPartReference: 'B2C_REF_001'
);

if ($response->isSuccessful() && $response->isApiSuccess()) {
    echo "✓ Payment sent successfully!\n";
    echo "Transaction ID: " . $response->getTransactionId() . "\n";
    echo "Conversation ID: " . $response->getConversationId() . "\n";
} else {
    echo "✗ Payment failed\n";
}

// B2C Async Transaction
echo "\n2. B2C Async Transaction:\n";
$asyncResponse = $mpesa->send(
    transactionReference: 'B2C_ASYNC_001',
    to: '258841234567',
    amount: 75,
    thirdPartReference: 'B2C_ASYNC_REF_001',
    isAsync: true
);

if ($asyncResponse->isTransactionInitiated()) {
    echo "✓ Async payment initiated!\n";
    echo "Conversation ID: " . $asyncResponse->getConversationId() . "\n";
    echo "Third Party Reference: " . $asyncResponse->getThirdPartyReference() . "\n";
}

echo "\n=== B2C Transaction Example Completed ===\n";
