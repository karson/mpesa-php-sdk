<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Karson\MpesaPhpSdk\Mpesa;
use Karson\MpesaPhpSdk\Constants\TransactionStatus;

echo "=== Transaction Status Example ===\n\n";

$mpesa = new Mpesa(
    publicKey: 'your_public_key_here',
    apiKey: 'your_api_key_here',
    isTest: true, // true for sandbox, false for production
    providerCode: '171717'
);

// Check transaction status
echo "1. Transaction Status Query:\n";
$statusResponse = $mpesa->status(
    thirdPartyReference: 'REF_001',
    queryReference: 'TXN_001'
);

if ($statusResponse->isSuccessful()) {
    echo "✓ Status query successful!\n";
    echo "Transaction ID: " . $statusResponse->getTransactionId() . "\n";
    echo "Transaction Status: " . $statusResponse->getTransactionStatus() . "\n";
    echo "Amount: " . $statusResponse->getAmount() . " " . $statusResponse->getCurrency() . "\n";
    echo "Receiver Party: " . $statusResponse->getReceiverParty() . "\n";
    
    // Check status using constants
    $status = $statusResponse->getTransactionStatus();
    if (TransactionStatus::isCompleted($status)) {
        echo "✓ Transaction completed successfully\n";
    } elseif (TransactionStatus::isPending($status)) {
        echo "⏳ Transaction is pending\n";
    } elseif (TransactionStatus::isFailed($status)) {
        echo "✗ Transaction failed\n";
    }
    
    // Using response methods
    if ($statusResponse->isTransactionCompleted()) {
        echo "✓ Transaction is completed (using method)\n";
    }
} else {
    echo "✗ Status query failed\n";
}

echo "\n=== Transaction Status Example Completed ===\n";
