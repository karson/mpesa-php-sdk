# Migration Guide: v1.x to v2.0

This guide helps you migrate from M-Pesa PHP SDK v1.x to v2.0.

## Overview

Version 2.0 introduces significant improvements including:
- Intelligent token management with expiration
- Comprehensive parameter validation
- Custom exception system
- B2B transaction support
- Enhanced response handling

## Breaking Changes

### 1. Exception Handling

**Before (v1.x):**
```php
try {
    $response = $mpesa->receive('TXN001', '258841234567', 100, 'REF001');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

**After (v2.0):**
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
    echo "Auth Error: " . $e->getMessage();
} catch (ApiException $e) {
    echo "API Error: " . $e->getMessage();
    echo "Response Code: " . $e->getResponseCode();
}
```

### 2. Token Management

**Before (v1.x):**
```php
$token = $mpesa->getToken();
// Token was regenerated on every call
```

**After (v2.0):**
```php
$token = $mpesa->getToken();
// Token is cached and reused until expiration

// Access token manager for advanced control
$tokenManager = $mpesa->getTokenManager();

// To force token regeneration, clear the current token
$tokenManager->clearToken();
```

### 3. Response Handling

**Before (v1.x):**
```php
if ($response->isSuccessful()) {
    // Handle success
}
```

**After (v2.0):**
```php
if ($response->isSuccessful()) {
    // HTTP request was successful
    
    if ($response->isApiSuccess()) {
        // M-Pesa API returned success code
        echo "Transaction ID: " . ($response->getTransactionId() ?? 'N/A');
    }
}
```

## New Features

### 1. Parameter Validation

```php
use Karson\MpesaPhpSdk\Validation\ParameterValidator;

// Validate before making requests
if (!ParameterValidator::validateMSISDN('258841234567')) {
    throw new InvalidArgumentException('Invalid phone number');
}

// Validation is now automatic on all transaction methods
```

### 2. Constants Usage

```php
use Karson\MpesaPhpSdk\Constants\ResponseCodes;
use Karson\MpesaPhpSdk\Constants\TransactionStatus;

// Check response codes
if ($response->getApiResponseCode() === ResponseCodes::SUCCESS) {
    echo "Success!";
}

// Check transaction status
if (TransactionStatus::isCompleted($status)) {
    echo "Transaction completed";
}
```

### 3. B2B Transactions (New)

```php
// New B2B method
$response = $mpesa->b2b(
    transactionReference: 'TXN001',
    amount: 1000,
    thirdPartReference: 'REF001',
    primaryPartyCode: '171717',
    receiverPartyCode: '979797'
);
```

## Migration Steps

### Step 1: Update Dependencies

```bash
composer update karson/mpesa-php-sdk
```

### Step 2: Update Exception Handling

Replace generic `Exception` catches with specific exception types:

```php
// Replace this pattern throughout your code
try {
    // M-Pesa operations
} catch (Exception $e) {
    // Generic handling
}

// With this pattern
try {
    // M-Pesa operations
} catch (ValidationException $e) {
    // Handle validation errors
} catch (AuthenticationException $e) {
    // Handle auth errors  
} catch (ApiException $e) {
    // Handle API errors
} catch (Exception $e) {
    // Handle other errors
}
```

### Step 3: Update Response Handling

If you were checking response data directly, update to use new methods:

```php
// Old way
$responseData = json_decode($response->response, true);
if ($responseData['output_ResponseCode'] === 'INS-0') {
    // Success
}

// New way
if ($response->isApiSuccess()) {
    // Use specific getter methods
    $transactionId = $response->getTransactionId();
    $conversationId = $response->getConversationId();
}
```

### Step 4: Leverage Token Management

For long-running applications, add proactive token management:

```php
// In long-running processes
$tokenManager = $mpesa->getTokenManager();

while ($process->isRunning()) {
    // Clear token periodically to force regeneration
    if ($shouldRefreshToken) {
        echo "Clearing token for regeneration...";
        $tokenManager->clearToken();
    }
    
    // Your existing M-Pesa operations
    $response = $mpesa->receive(...);
    
    sleep(60);
}
```

### Step 5: Add Parameter Validation (Optional)

For additional safety, add explicit validation:

```php
use Karson\MpesaPhpSdk\Validation\ParameterValidator;

// Before making transactions
$params = [
    'transactionReference' => $txnRef,
    'customerMSISDN' => $phone,
    'amount' => $amount,
    'thirdPartyReference' => $ref,
    'serviceProviderCode' => $providerCode
];

$errors = ParameterValidator::validateC2BParameters($params);
if (!empty($errors)) {
    throw new InvalidArgumentException('Invalid parameters: ' . implode(', ', $errors));
}
```

## Testing Your Migration

1. **Run existing tests** to ensure basic functionality works
2. **Test error scenarios** to verify exception handling
3. **Monitor token usage** in production for efficiency gains
4. **Validate all transaction types** you use

## Benefits After Migration

- **Better Error Handling**: Specific exceptions with detailed context
- **Improved Performance**: Token caching reduces API calls
- **Enhanced Reliability**: Automatic parameter validation
- **Better Debugging**: Detailed error messages and response codes
- **Future-Proof**: Support for new M-Pesa features like B2B

## Support

If you encounter issues during migration:

1. Check the [CHANGELOG.md](CHANGELOG.md) for detailed changes
2. Review the [README.md](README.md) for updated examples
3. Run the example files in the `examples/` directory
4. Check the test suite for usage patterns

## Rollback Plan

If you need to rollback to v1.x:

```bash
composer require karson/mpesa-php-sdk:^1.4
```

Note: You'll lose the new features but maintain backward compatibility.
