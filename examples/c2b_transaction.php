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
    from: '258848283607',
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

// // C2B Async Transaction - Retorna AsyncResponse
// echo "\n2. C2B Async Transaction:\n";
// $asyncResponse = $mpesa->c2bAsync(
//     transactionReference: 'C2B_ASYNC_001' . time(),
//     from: '258848283607',
//     amount: 200,
//     thirdPartReference: 'ASYNC_REF_001',
// );

// // Agora o IDE sabe que é AsyncResponse e tem os métodos específicos
// if ($asyncResponse instanceof AsyncResponse && $asyncResponse->isTransactionInitiated()) {
//     echo "✓ Async transaction initiated!\n";
//     echo "Conversation ID: " . $asyncResponse->getConversationId() . "\n";
//     echo "Third Party Reference: " . $asyncResponse->getThirdPartyReference() . "\n";
//     echo "Response Code: " . $asyncResponse->getResponseCode() . "\n";
    
//     if ($asyncResponse->isAcceptedForProcessing()) {
//         echo "✓ Transaction accepted for processing\n";
//     }
// } else {
//     echo "✗ Async transaction failed to initiate\n";
// }

// echo "\n=== C2B Transaction Example Completed ===\n";
