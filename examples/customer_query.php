<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Karson\MpesaPhpSdk\Mpesa;

echo "=== Customer Query Example ===\n\n";

$mpesa = new Mpesa(
    publicKey: 'your_public_key_here',
    apiKey: 'your_api_key_here',
    isTest: true, // true for sandbox, false for production
    providerCode: '171717'
);

// Query customer name
echo "1. Customer Name Query:\n";
$customerResponse = $mpesa->queryCustomerName('258841234567');

if ($customerResponse->isCustomerFound()) {
    echo "✓ Customer found!\n";
    echo "MSISDN: " . $customerResponse->getCustomerMSISDN() . "\n";
    echo "First Name: " . $customerResponse->getFirstName() . "\n";
    echo "Last Name: " . $customerResponse->getLastName() . "\n";
    echo "Full Name: " . $customerResponse->getCustomerName() . "\n";
} else {
    echo "✗ Customer not found\n";
}

echo "\n=== Customer Query Example Completed ===\n";
