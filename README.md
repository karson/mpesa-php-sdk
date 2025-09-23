# Mpesa Mozambique PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/karson/mpesa-php-sdk.svg?style=flat-square)](https://packagist.org/packages/karson/mpesa-php-sdk)
[![Build Status](https://img.shields.io/travis/karson/mpesa-php-sdk/master.svg?style=flat-square)](https://travis-ci.org/karson/mpesa-php-sdk)
[![Quality Score](https://img.shields.io/scrutinizer/g/karson/mpesa-php-sdk.svg?style=flat-square)](https://scrutinizer-ci.com/g/karson/mpesa-php-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/karson/mpesa-php-sdk.svg?style=flat-square)](https://packagist.org/packages/karson/mpesa-php-sdk)

A comprehensive PHP SDK for integrating with M-Pesa Mozambique APIs. This package provides a clean, modern interface for all M-Pesa operations with robust error handling, callback processing, and extensive validation.

## Features

- **🚀 Modern PHP 8.1+ Support**: Built with modern PHP features including typed properties, named arguments, and enums
- **📦 Unified Response Architecture**: Streamlined response classes with eliminated code duplication (~90% reduction)
- **🔄 Callback Handler System**: Complete callback processing with event-driven architecture (TODO)
- **✅ Type Safety**: Strongly typed responses with specific methods for each operation
- **🏗️ Clean Architecture**: Organized structure with dedicated response classes and clear inheritance
- **🔐 Security First**: Built-in signature validation, parameter sanitization, and secure token management
- **📊 Transaction Operations**: Full support for C2B, B2C, B2B transactions with sync/async modes
- **🔍 Query Operations**: Transaction status queries and customer name lookups
- **💰 Refund/Reversal**: Complete transaction reversal support with partial refund capabilities
- **🎯 Smart Validation**: Comprehensive parameter validation with detailed error messages
- **📱 Laravel Integration**: Native Laravel service provider with configuration publishing
- **🔧 Developer Experience**: Extensive examples, comprehensive documentation, and debugging tools

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
    serviceProviderCode: '171717' // Your service provider code
);
```

### Customer to Business (C2B) Transactions

```php
// C2B Transaction (Unified API)
$response = $mpesa->c2b(
    transactionReference: 'TXN001',
    from: '258841234567',
    amount: 100,
    thirdPartReference: 'REF001'
);

if ($response->isTransactionSuccessful()) {
    echo "Transaction ID: " . $response->getTransactionId();
    echo "Conversation ID: " . $response->getConversationId();
    echo "Third Party Reference: " . $response->getThirdPartyReference();
} else {
    echo "Error: " . $response->getResponseDescription();
    echo "Error Code: " . $response->getResponseCode();
}

// Check transaction status
if ($response->isTransactionInitiated()) {
    echo "Transaction initiated. Use Conversation ID for status tracking.";
}
```

### Business to Customer (B2C) Transactions

```php
// B2C Transaction (Unified API)
$response = $mpesa->b2c(
    customerMSISDN: '258841234567',
    amount: 100,
    transactionReference: 'TXN002',
    thirdPartReference: 'REF002'
);

if ($response->isTransactionSuccessful()) {
    echo "Transaction ID: " . $response->getTransactionId();
    echo "Conversation ID: " . $response->getConversationId();
    echo "Third Party Reference: " . $response->getThirdPartyReference();
} else {
    echo "Error: " . $response->getResponseDescription();
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
echo "Currency: " . $response->getCurrency();
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
// B2B Transaction (Unified API)
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
    echo "Third Party Reference: " . $response->getThirdPartyReference();
} else {
    echo "Error: " . $response->getResponseDescription();
}
```

### Transaction Refund/Reversal

```php
$response = $mpesa->reversal(
    transactionID: 'TXN123456',
    securityCredential: 'your_security_credential',
    initiatorIdentifier: 'your_initiator',
    thirdPartyReference: 'REF004',
    reversalAmount: '50' // Optional: partial refund
);

if ($response->isReversalSuccessful()) {
    echo "Refund Transaction ID: " . $response->getReversalTransactionId();
    echo "Refund Amount: " . $response->getReversalAmount();
    
    if ($response->isPartialReversal()) {
        echo "This was a partial refund";
    }
} else {
    echo "Refund failed: " . $response->getResponseDescription();
}
```


### Response Objects

All methods return strongly typed response objects based on a unified architecture:

#### BaseResponse (Unified Response Class)

All transaction responses now inherit from `BaseResponse` with common methods:

```php
// Common methods available on all responses
$response->getTransactionId();          // Transaction ID
$response->getConversationId();         // Conversation ID for tracking
$response->getResponseCode();           // M-Pesa response code
$response->getResponseDescription();    // Response description
$response->getThirdPartyReference();    // Third party reference
$response->isTransactionSuccessful();   // Check if transaction succeeded
$response->isTransactionInitiated();    // Check if transaction was initiated
$response->getStatusCode();             // HTTP status code
$response->getRawResponse();            // Raw API response
$response->isSuccessful();              // HTTP success check
$response->isApiSuccess();              // M-Pesa API success check
```

#### Transaction Response Classes

- **TransactionResponse**: Unified response for C2B, B2C, B2B transactions
- **TransactionStatusResponse**: For transaction status queries  
- **CustomerNameResponse**: For customer name lookups
- **ReversalResponse**: For refund/reversal operations

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

#### Response Class Architecture

The SDK v2.0 features a completely refactored response architecture that eliminates ~90% of code duplication:

```
BaseResponse (unified base class)
├── TransactionResponse (unified for C2B, B2C, B2B)
├── TransactionStatusResponse (transaction status queries)
├── CustomerNameResponse (customer name lookups)
└── ReversalResponse (refund/reversal operations)
```

**Key Improvements:**
- **Unified API**: All transaction types (C2B, B2C, B2B) now return the same `TransactionResponse` class
- **Eliminated Duplication**: Removed redundant sync/async response classes
- **Better Type Safety**: Specific methods for each response type with proper return types
- **Consistent Interface**: All responses share common methods from `BaseResponse`
- **Simplified Usage**: No need to remember different method names for different transaction types

## Project Structure

The SDK v2.0 features a streamlined, organized structure:

```
src/
├── Mpesa.php                    # Main SDK class with unified API
├── Auth/                        # Authentication management
│   └── TokenManager.php        # Token generation and caching
├── Constants/                   # API constants and enums
│   ├── ResponseCodes.php       # Response code constants
│   └── TransactionStatus.php   # Transaction status constants
├── Validation/                  # Parameter validation
│   └── ParameterValidator.php  # Input validation utilities
├── Exceptions/                  # Custom exceptions
│   ├── ValidationException.php
│   ├── AuthenticationException.php
│   ├── ApiException.php
│   └── CallbackException.php
├── Providers/
│   └── ServiceProvider.php     # Laravel service provider
├── config/
│   └── mpesa.php               # Configuration file
├── Callback/                    # Callback handling system (NEW)
│   ├── CallbackHandler.php     # Main callback processor
│   └── Events/                  # Callback event classes
│       ├── CallbackEvent.php   # Base event class
│       ├── TransactionCompletedEvent.php
│       └── TransactionFailedEvent.php
└── response/                    # Unified response classes
    ├── BaseResponse.php         # Unified base response
    ├── TransactionResponse.php  # For C2B, B2C, B2B transactions
    ├── TransactionStatusResponse.php
    ├── CustomerNameResponse.php
    └── ReversalResponse.php
```

**Key Architectural Improvements:**
- **Unified Response System**: Single `TransactionResponse` class for all transaction types
- **Callback Handler System**: Complete event-driven callback processing
- **Streamlined Structure**: Eliminated redundant directories and classes
- **Better Organization**: Clear separation of concerns with dedicated folders
- **Enhanced Security**: Comprehensive exception handling and validation

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
use Karson\MpesaPhpSdk\Callback\CallbackHandler;

class PaymentController extends Controller
{
    public function __construct(
        private Mpesa $mpesa,
        private CallbackHandler $callbackHandler
    ) {
    }
    
    public function processPayment(Request $request)
    {
        $response = $this->mpesa->c2b(
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
                'conversation_id' => $response->getConversationId(),
                'third_party_reference' => $response->getThirdPartyReference()
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => $response->getResponseDescription(),
            'error_code' => $response->getResponseCode()
        ], 400);
    }
    
    public function handleCallback(Request $request)
    {

            $response = $request->getContent(),
            
          ...
        
    }
}
```

## Error Handling

All response objects provide comprehensive error information with the unified API:

```php
$response = $mpesa->c2b('TXN001', '258841234567', 100, 'REF001');

// Check HTTP status
if (!$response->isSuccessful()) {
    echo "HTTP Error: " . $response->getStatusCode();
}

// Check transaction status
if (!$response->isTransactionSuccessful()) {
    echo "Transaction Error: " . $response->getResponseDescription();
    echo "Error Code: " . $response->getResponseCode();
}

// Check if transaction was initiated (for async processing)
if ($response->isTransactionInitiated()) {
    echo "Transaction initiated. Conversation ID: " . $response->getConversationId();
}

// Get raw response for debugging
var_dump($response->getRawResponse());
```

## Response Structure

### Unified Transaction Responses

All transaction methods (C2B, B2C, B2B) now return a unified `TransactionResponse` object:

```php
// All transaction types use the same response structure
$c2bResponse = $mpesa->c2b('TXN001', '258841234567', 100, 'REF001');
$b2cResponse = $mpesa->b2c('258841234567', 100, 'TXN002', 'REF002');
$b2bResponse = $mpesa->b2b('TXN003', 100, 'REF003', '171717', '979797');

// All responses have the same methods available
foreach ([$c2bResponse, $b2cResponse, $b2bResponse] as $response) {
    if ($response->isTransactionSuccessful()) {
        echo "Transaction ID: " . $response->getTransactionId();
        echo "Conversation ID: " . $response->getConversationId();
        echo "Third Party Reference: " . $response->getThirdPartyReference();
    }
}
```

### Transaction Status Tracking

Use the conversation ID to track transaction status:

```php
// Initiate transaction
$response = $mpesa->c2b('TXN001', '258841234567', 100, 'REF001');

if ($response->isTransactionInitiated()) {
    $conversationId = $response->getConversationId();
    
    // Later, check the status
    $statusResponse = $mpesa->queryTransactionStatus('REF001', 'QUERY001');
    
    if ($statusResponse->isTransactionCompleted()) {
        echo "Transaction completed successfully";
        echo "Amount: " . $statusResponse->getAmount();
        echo "Currency: " . $statusResponse->getCurrency();
    } elseif ($statusResponse->isTransactionPending()) {
        echo "Transaction is still pending";
    } elseif ($statusResponse->isTransactionFailed()) {
        echo "Transaction failed";
    }
}
```

## Exception Handling

The SDK provides comprehensive error handling with custom exceptions:

```php
use Karson\MpesaPhpSdk\Exceptions\ValidationException;
use Karson\MpesaPhpSdk\Exceptions\AuthenticationException;
use Karson\MpesaPhpSdk\Exceptions\ApiException;
use Karson\MpesaPhpSdk\Exceptions\CallbackException;

try {
    $response = $mpesa->c2b('TXN001', '258841234567', 100, 'REF001');
    
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
    
} catch (CallbackException $e) {
    echo "Callback Error: " . $e->getMessage();
    if ($e->getCallbackData()) {
        echo "Callback Data: " . json_encode($e->getCallbackData());
    }
    
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage();
}
```

### Exception Types

- **ValidationException**: Thrown when input parameters fail validation
- **AuthenticationException**: Thrown when API authentication fails
- **ApiException**: Thrown when M-Pesa API returns an error

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

## What's New in v2.0

### 🚀 Major Improvements

- **Unified API**: All transaction methods (C2B, B2C, B2B) now return the same `TransactionResponse` class
- **Eliminated Code Duplication**: ~90% reduction in response class code through unified architecture
- **Enhanced Type Safety**: Better IDE support with specific typed methods
- **Improved Performance**: Optimized response parsing and memory usage
- **Better Security**: Enhanced validation, signature verification, and error handling

### 🔄 Breaking Changes

#### Response Classes Unified
```php
// Before v2.0 (multiple response classes)
$c2bResponse = $mpesa->c2b(...); // Returns C2BSyncResponse
$b2cResponse = $mpesa->b2c(...); // Returns B2CSyncResponse  
$b2bResponse = $mpesa->b2b(...); // Returns B2BSyncResponse

// v2.0+ (unified response)
$c2bResponse = $mpesa->c2b(...); // Returns TransactionResponse
$b2cResponse = $mpesa->b2c(...); // Returns TransactionResponse
$b2bResponse = $mpesa->b2b(...); // Returns TransactionResponse
```

#### Removed getData() Method
```php
// Before v2.0
$transactionId = $response->getData()['output_TransactionID'];

// v2.0+ (better type safety)
$transactionId = $response->getTransactionId();
```

### 📦 New Features


#### Enhanced Response Methods
```php
// New methods available on all responses
$response->getThirdPartyReference();  // Available on all transaction responses
$response->isTransactionInitiated();  // Check if async transaction started
$response->isApiSuccess();            // Check M-Pesa API success
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
