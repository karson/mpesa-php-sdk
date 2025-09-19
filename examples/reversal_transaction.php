<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Karson\MpesaPhpSdk\Mpesa;

echo "=== Reversal Transaction Example ===\n\n";

$mpesa = new Mpesa(
    publicKey: 'your_public_key_here',
    apiKey: 'your_api_key_here',
    isTest: true, // true for sandbox, false for production
    providerCode: '171717'
);

// Reverse a transaction
echo "1. Transaction Reversal:\n";
$reversalResponse = $mpesa->reverse(
    transactionId: 'ORIGINAL_TXN_ID_123',
    amount: 100,
    thirdPartyReference: 'REVERSAL_REF_001'
);

if ($reversalResponse->isReversalSuccessful()) {
    echo "✓ Transaction reversed successfully!\n";
    echo "Original Transaction ID: " . $reversalResponse->getTransactionId() . "\n";
    echo "Reversal Transaction ID: " . $reversalResponse->getReversalTransactionId() . "\n";
    echo "Reversal Amount: " . $reversalResponse->getReversalAmount() . "\n";
    echo "Conversation ID: " . $reversalResponse->getConversationId() . "\n";
    
    if ($reversalResponse->isPartialReversal()) {
        echo "⚠ This was a partial reversal\n";
    } else {
        echo "✓ Full transaction reversal\n";
    }
} else {
    echo "✗ Transaction reversal failed\n";
}

echo "\n=== Reversal Transaction Example Completed ===\n";
