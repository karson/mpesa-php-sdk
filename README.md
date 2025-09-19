# Mpesa Mozambique PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/karson/mpesa-php-sdk.svg?style=flat-square)](https://packagist.org/packages/karson/mpesa-php-sdk)
[![Build Status](https://img.shields.io/travis/karson/mpesa-php-sdk/master.svg?style=flat-square)](https://travis-ci.org/karson/mpesa-php-sdk)
[![Quality Score](https://img.shields.io/scrutinizer/g/karson/mpesa-php-sdk.svg?style=flat-square)](https://scrutinizer-ci.com/g/karson/mpesa-php-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/karson/mpesa-php-sdk.svg?style=flat-square)](https://packagist.org/packages/karson/mpesa-php-sdk)

This package seeks to help PHP developers implement the various M-Pesa APIs without much hassle. It is based on the REST API whose documentation is available on https://developer.mpesa.vm.co.mz/.

## Features

- **Typed Response Classes**: All API responses are returned as strongly typed objects with specific methods for each operation
- **Organized Structure**: Response classes are organized in dedicated folders (C2B, B2C, B2B, etc.) for better maintainability
- **Synchronous & Asynchronous Support**: Support for both sync and async transaction processing
- **C2B Transactions**: Customer to Business payments with detailed response handling
- **B2C Transactions**: Business to Customer payments with comprehensive response objects
- **B2B Transactions**: Business to Business payments with full API support
- **Transaction Status**: Query transaction status with structured responses
- **Customer Name Query**: Retrieve customer information with obfuscated names
- **Refund/Reversal**: Process transaction reversals with detailed feedback
- **Parameter Validation**: Built-in validation for all API parameters
- **Response Code Constants**: Predefined constants for all API response codes
- **Transaction Status Constants**: Standardized transaction status handling
- **Laravel Integration**: Built-in Laravel service provider for easy integration

## Installation

You can install the package via composer:

```bash
composer require karson/mpesa-php-sdk
```

## Usage

### Basic Setup

```php
use Karson\MpesaPhpSdk\Mpesa;

// Initialize with your credentials from M-Pesa Developer Console (https://developer.mpesa.vm.co.mz/)
$mpesa = new Mpesa(
    publicKey: 'your_public_key',
    apiKey: 'your_api_key', 
    isTest: true, // false for production
    providerCode: '171717' // Your service provider code
);
```

### Customer to Business (C2B) Transactions

```php
// Synchronous C2B Transaction
$response = $mpesa->receive(
    transactionReference: 'TXN001',
    from: '258841234567',
    amount: 100,
    thirdPartReference: 'REF001'
);

if ($response->isTransactionSuccessful()) {
    echo "Transaction ID: " . $response->getTransactionId();
    echo "Conversation ID: " . $response->getConversationId();
} else {
    echo "Error: " . $response->getResponseDescription();
}

// Asynchronous C2B Transaction
$response = $mpesa->receive(
    transactionReference: 'TXN001',
    from: '258841234567', 
    amount: 100,
    thirdPartReference: 'REF001',
    isAsync: true
);

if ($response->isTransactionInitiated()) {
    echo "Transaction initiated. Conversation ID: " . $response->getConversationId();
    // Use the Conversation ID to check status later
}
```

### Business to Customer (B2C) Transactions

```php
// Synchronous B2C Transaction
$response = $mpesa->send(
    to: '258841234567',
    amount: 100,
    transactionReference: 'TXN002',
    thirdPartReference: 'REF002'
);

if ($response->isTransactionSuccessful()) {
    echo "Transaction ID: " . $response->getTransactionId();
    echo "Conversation ID: " . $response->getConversationId();
}

// Asynchronous B2C Transaction
$response = $mpesa->send(
    to: '258841234567',
    amount: 100, 
    transactionReference: 'TXN002',
    thirdPartReference: 'REF002',
    isAsync: true
);

if ($response->isTransactionInitiated()) {
    echo "Payment initiated. Conversation ID: " . $response->getConversationId();
}
```

### Transaction Status Query

```php
$response = $mpesa->status(
    thirdPartyReference: 'REF001',
    queryReference: 'QUERY001'
);

// Access status information
echo "Transaction Status: " . $response->getTransactionStatus();
echo "Amount: " . $response->getAmount();
```

### Customer Name Query

```php
$response = $mpesa->queryCustomerName(
    customerMSISDN: '258841234567',
    thirdPartyReference: 'REF003'
);

if ($response->isSuccessful()) {
    echo "Customer Name: " . $response->getCustomerName();
    echo "First Name: " . $response->getFirstName();
    echo "Last Name: " . $response->getLastName();
}
```

### Business to Business (B2B) Transactions

```php
// Synchronous B2B Transaction
$response = $mpesa->b2b(
    transactionReference: 'TXN003',
    amount: 100,
    thirdPartReference: 'REF003',
    primaryPartyCode: '171717', // Sender business code
    receiverPartyCode: '979797'  // Receiver business code
);

if ($response->isTransactionSuccessful()) {
    echo "B2B Transaction ID: " . $response->getTransactionId();
    echo "Conversation ID: " . $response->getConversationId();
}

// Asynchronous B2B Transaction
$response = $mpesa->b2b(
    transactionReference: 'TXN003',
    amount: 100,
    thirdPartReference: 'REF003',
    primaryPartyCode: '171717',
    receiverPartyCode: '979797',
    isAsync: true
);

if ($response->isTransactionInitiated()) {
    echo "B2B Transaction initiated. Conversation ID: " . $response->getConversationId();
}
```

### Transaction Refund/Reversal

```php
$response = $mpesa->refund(
    transactionID: 'TXN123456',
    securityCredential: 'your_security_credential',
    initiatorIdentifier: 'your_initiator',
    thirdPartyReference: 'REF004',
    reversalAmount: '50' // Optional: partial refund
);

if ($response->isSuccessful()) {
    echo "Refund Transaction ID: " . $response->getReversalTransactionId();
}
```

### Response Objects

All methods return strongly typed response objects with specific methods:

#### C2B Response Methods
- `getTransactionId()`: Get the transaction ID (sync only)
- `getConversationId()`: Get the conversation ID
- `getResponseCode()`: Get the response code
- `getResponseDescription()`: Get the response description
- `isTransactionSuccessful()`: Check if transaction was successful (sync only)
- `isTransactionInitiated()`: Check if async transaction was initiated

#### B2C Response Methods
- `getTransactionId()`: Get the transaction ID (sync only)
- `getConversationId()`: Get the conversation ID
- `getThirdPartyReference()`: Get the third party reference
- `getResponseCode()`: Get the response code
- `getResponseDescription()`: Get the response description
- `isTransactionSuccessful()`: Check if transaction was successful (sync only)
- `isTransactionInitiated()`: Check if async transaction was initiated

#### B2B Response Methods
- `getTransactionId()`: Get the transaction ID (sync only)
- `getConversationId()`: Get the conversation ID
- `getThirdPartyReference()`: Get the third party reference
- `getResponseCode()`: Get the response code
- `getResponseDescription()`: Get the response description
- `isTransactionSuccessful()`: Check if transaction was successful (sync only)
- `isTransactionInitiated()`: Check if async transaction was initiated

#### Transaction Status Response Methods
- `getTransactionId()`: Get the transaction ID
- `getConversationId()`: Get the conversation ID
- `getTransactionStatus()`: Get the current transaction status
- `getAmount()`: Get the transaction amount
- `getCurrency()`: Get the transaction currency
- `getReceiverParty()`: Get the receiver party information
- `getTransactionCompletedDateTime()`: Get completion timestamp
- `isTransactionCompleted()`: Check if transaction is completed
- `isTransactionPending()`: Check if transaction is pending
- `isTransactionFailed()`: Check if transaction failed

#### Customer Name Response Methods
- `getCustomerMSISDN()`: Get the customer phone number
- `getFirstName()`: Get the customer's first name (obfuscated)
- `getSecondName()`: Get the customer's second name (obfuscated)
- `getCustomerName()`: Get the full customer name
- `isCustomerFound()`: Check if customer was found

#### Refund/Reversal Response Methods
- `getTransactionId()`: Get the original transaction ID
- `getReversalTransactionId()`: Get the reversal transaction ID
- `getReversalAmount()`: Get the reversed amount (for partial reversals)
- `getConversationId()`: Get the conversation ID
- `isReversalSuccessful()`: Check if reversal was successful
- `isPartialReversal()`: Check if it was a partial reversal

#### Response Class Hierarchy

The SDK uses a clean inheritance hierarchy to eliminate code duplication:

```
BaseResponse (abstract)
├── AsyncResponse (abstract) - For asynchronous responses
│   ├── C2BAsyncResponse
│   ├── B2CAsyncResponse
│   └── B2BAsyncResponse
└── SyncResponse (abstract) - For synchronous responses
    ├── C2BSyncResponse
    ├── B2CSyncResponse
    └── B2BSyncResponse
```

#### Common Response Methods
- `getStatusCode()`: Get HTTP status code
- `getRawResponse()`: Get raw API response
- `isSuccessful()`: Check if HTTP request was successful
- `isApiSuccess()`: Check if M-Pesa API returned success code

#### Async Response Methods
- `getThirdPartyReference()`: Get third party reference
- `getConversationId()`: Get conversation ID for status tracking
- `isTransactionInitiated()`: Check if transaction was initiated
- `isAcceptedForProcessing()`: Check if accepted for async processing

#### Sync Response Methods
- `getTransactionId()`: Get transaction ID
- `getConversationId()`: Get conversation ID
- `getResponseCode()`: Get M-Pesa response code
- `isTransactionSuccessful()`: Check if transaction completed successfully

## Project Structure

The SDK is organized with a clean, modular structure:

```
src/
├── Mpesa.php                    # Main SDK class
├── Constants/                   # API constants and enums
│   ├── ResponseCodes.php       # Response code constants
│   └── TransactionStatus.php   # Transaction status constants
├── Validation/                  # Parameter validation
│   └── ParameterValidator.php  # Input validation utilities
├── Providers/
│   └── ServiceProvider.php     # Laravel service provider
├── config/
│   └── mpesa.php               # Configuration file
└── Response/                   # Response handling classes
    ├── BaseResponse.php        # Base response class
    ├── AsyncResponse.php       # Base for async responses
    ├── SyncResponse.php        # Base for sync responses
    ├── C2B/                    # Customer to Business responses
    │   ├── C2BSyncResponse.php
    │   ├── C2BAsyncResponse.php
    │   └── C2BResponseFactory.php
    ├── B2C/                    # Business to Customer responses
    │   ├── B2CSyncResponse.php
    │   ├── B2CAsyncResponse.php
    │   └── B2CResponseFactory.php
    ├── B2B/                    # Business to Business responses
    │   ├── B2BSyncResponse.php
    │   ├── B2BAsyncResponse.php
    │   └── B2BResponseFactory.php
    ├── Status/                 # Transaction status responses
    │   └── TransactionStatusResponse.php
    ├── Query/                  # Customer query responses
    │   └── CustomerNameResponse.php
    └── Refund/                 # Refund/reversal responses
        └── ReversalResponse.php
```

This organized structure makes it easy to:
- Find specific response classes
- Maintain and extend functionality
- Understand the codebase at a glance
- Add new response types in appropriate folders
- Use validation utilities and constants
- Implement consistent error handling

## API Reference

For detailed API documentation including all endpoints, request/response parameters, and examples, see the [API Documentation](API.md).

## Laravel Integration

### Installation in Laravel

Add the following environment variables to your `.env` file:

```env
MPESA_API_KEY="Your API Key"
MPESA_PUBLIC_KEY="Your Public Key"
MPESA_ENV=test # 'live' for production environment
MPESA_SERVICE_PROVIDER_CODE=171717
```

### Service Provider Registration

The package includes a Laravel service provider that automatically registers the M-Pesa service. You can inject it into your controllers:

```php
use Karson\MpesaPhpSdk\Mpesa;

class PaymentController extends Controller
{
    public function __construct(private Mpesa $mpesa)
    {
    }
    
    public function processPayment(Request $request)
    {
        $response = $this->mpesa->receive(
            transactionReference: $request->transaction_ref,
            from: $request->phone_number,
            amount: $request->amount,
            thirdPartReference: $request->reference
        );
        
        if ($response->isTransactionSuccessful()) {
            // Handle successful payment
            return response()->json([
                'success' => true,
                'transaction_id' => $response->getTransactionId(),
                'conversation_id' => $response->getConversationId()
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => $response->getResponseDescription()
        ], 400);
    }
}
```

## Error Handling

All response objects provide comprehensive error information:

```php
$response = $mpesa->receive('TXN001', '258841234567', 100, 'REF001');

// Check HTTP status
if (!$response->isSuccessful()) {
    echo "HTTP Error: " . $response->getStatusCode();
}

// Check transaction status
if (!$response->isTransactionSuccessful()) {
    echo "Transaction Error: " . $response->getResponseDescription();
    echo "Error Code: " . $response->getResponseCode();
}

// Get raw response for debugging
var_dump($response->getRawResponse());
```

## Response Structure

### Synchronous Responses
Synchronous responses include immediate transaction results with transaction IDs.

### Asynchronous Responses  
Asynchronous responses provide a conversation ID that can be used to query the transaction status later using the `status()` method.

```php
// Initiate async transaction
$response = $mpesa->receive('TXN001', '258841234567', 100, 'REF001', true);

if ($response->isTransactionInitiated()) {
    $conversationId = $response->getConversationId();
    
    // Later, check the status
    $statusResponse = $mpesa->status('REF001', $conversationId);
    echo "Final Status: " . $statusResponse->getTransactionStatus();
}
```

## Error Handling

The SDK provides comprehensive error handling with custom exceptions:

```php
use Karson\MpesaPhpSdk\Exceptions\ValidationException;
use Karson\MpesaPhpSdk\Exceptions\AuthenticationException;
use Karson\MpesaPhpSdk\Exceptions\ApiException;

try {
    $response = $mpesa->receive('TXN001', '258841234567', 100, 'REF001');
    
} catch (ValidationException $e) {
    echo "Validation Error: " . $e->getMessage();
    foreach ($e->getErrors() as $error) {
        echo "- " . $error . "\n";
    }
    
} catch (AuthenticationException $e) {
    echo "Authentication failed: " . $e->getMessage();
    
} catch (ApiException $e) {
    echo "API Error: " . $e->getMessage();
    echo "Response Code: " . $e->getResponseCode();
    echo "Response Description: " . $e->getResponseDescription();
    
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage();
}
```

## Token Management

The SDK includes intelligent token management with caching and optimization:

```php
// Get token manager
$tokenManager = $mpesa->getTokenManager();

// Get token (generated automatically if needed)
$token = $mpesa->getToken();
echo "Token: " . substr($token, 0, 20) . "...";

// Clear stored token
$tokenManager->clearToken();
```

### Token Management Features

- **Automatic Generation**: Tokens are generated automatically when needed
- **Smart Reuse**: Existing tokens are reused to improve performance
- **Manual Control**: Clear tokens when needed
- **Thread Safe**: Safe for use in concurrent environments

### Best Practices

```php
// Get token (automatically generated if needed)
$token = $mpesa->getToken();

// For long-running processes, clear and regenerate periodically
while ($process->isRunning()) {
    // Clear token periodically (e.g., every hour) to force regeneration
    if ($shouldRefreshToken) {
        $tokenManager->clearToken();
    }
    
    // Your API calls here (token will be generated automatically if needed)
    $response = $mpesa->receive(...);
    
    sleep(60);
}
```

## Parameter Validation

The SDK includes built-in parameter validation to ensure data integrity:

```php
use Karson\MpesaPhpSdk\Validation\ParameterValidator;

// Validate MSISDN format
if (!ParameterValidator::validateMSISDN('258841234567')) {
    echo "Invalid phone number format";
}

// Validate transaction parameters
$params = [
    'transactionReference' => 'TXN001',
    'customerMSISDN' => '258841234567',
    'amount' => 100,
    'thirdPartyReference' => 'REF001',
    'serviceProviderCode' => '171717'
];

$errors = ParameterValidator::validateC2BParameters($params);
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "Validation Error: " . $error . "\n";
    }
}
```

## Response Code Constants

Use predefined constants for consistent response handling:

```php
use Karson\MpesaPhpSdk\Constants\ResponseCodes;
use Karson\MpesaPhpSdk\Constants\TransactionStatus;

$response = $mpesa->receive('TXN001', '258841234567', 100, 'REF001');

// Check using constants
if ($response->getResponseCode() === ResponseCodes::SUCCESS) {
    echo "Transaction successful!";
}

// Check transaction status using constants
$statusResponse = $mpesa->status('REF001', 'QUERY001');
if (TransactionStatus::isCompleted($statusResponse->getTransactionStatus())) {
    echo "Transaction completed successfully";
} elseif (TransactionStatus::isPending($statusResponse->getTransactionStatus())) {
    echo "Transaction is still pending";
} elseif (TransactionStatus::isFailed($statusResponse->getTransactionStatus())) {
    echo "Transaction failed";
}
```

## Testing

The SDK includes comprehensive unit tests to ensure reliability:

```bash
# Run all tests
composer test

# Run tests with coverage
./vendor/bin/phpunit --coverage-html coverage

# Run specific test suite
./vendor/bin/phpunit tests/Unit/Constants/
./vendor/bin/phpunit tests/Unit/Validation/
```

### Test Coverage

The test suite covers:
- **Constants**: Response codes and transaction status validation
- **Validation**: Parameter validation for all transaction types
- **Authentication**: Token management and expiration
- **Response Classes**: All response parsing and methods
- **Exceptions**: Custom exception handling

### Running Tests

```bash
# Install dependencies
composer install

# Run tests
./vendor/bin/phpunit

# Run with verbose output
./vendor/bin/phpunit --verbose

# Run specific test
./vendor/bin/phpunit tests/Unit/Constants/ResponseCodesTest.php
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email karson@turbohost.co instead of using the issue tracker.

## Credits

- [Karson Adam](https://github.com/karson)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
