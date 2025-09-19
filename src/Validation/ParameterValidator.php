<?php

namespace Karson\MpesaPhpSdk\Validation;

class ParameterValidator
{
    /**
     * Validate MSISDN format (Mozambique mobile numbers)
     */
    public static function validateMSISDN(string $msisdn): bool
    {
        // Mozambique mobile numbers: 258 + 8/9 + 8 digits
        // Examples: 258843330333, 258840000000
        return preg_match('/^258[89]\d{8}$/', $msisdn) === 1;
    }
    
    /**
     * Validate transaction reference format
     */
    public static function validateTransactionReference(string $reference): bool
    {
        // Should be alphanumeric, max 50 characters
        return preg_match('/^[A-Za-z0-9]{1,50}$/', $reference) === 1;
    }
    
    /**
     * Validate third party reference format
     */
    public static function validateThirdPartyReference(string $reference): bool
    {
        // Should be alphanumeric, max 50 characters
        return preg_match('/^[A-Za-z0-9]{1,50}$/', $reference) === 1;
    }
    
    /**
     * Validate service provider code format
     */
    public static function validateServiceProviderCode(string $code): bool
    {
        // Should be numeric, typically 6 digits
        return preg_match('/^\d{6}$/', $code) === 1;
    }
    
    /**
     * Validate amount (should be positive number)
     */
    public static function validateAmount($amount): bool
    {
        if (is_string($amount)) {
            $amount = (float) $amount;
        }
        
        return is_numeric($amount) && $amount > 0;
    }
    
    /**
     * Validate security credential format
     */
    public static function validateSecurityCredential(string $credential): bool
    {
        // Should be non-empty string, max 255 characters
        return !empty($credential) && strlen($credential) <= 255;
    }
    
    /**
     * Validate initiator identifier format
     */
    public static function validateInitiatorIdentifier(string $identifier): bool
    {
        // Should be non-empty string, max 100 characters
        return !empty($identifier) && strlen($identifier) <= 100;
    }
    
    /**
     * Validate all required parameters for C2B transaction
     */
    public static function validateC2BParameters(array $params): array
    {
        $errors = [];
        
        if (!isset($params['transactionReference']) || !self::validateTransactionReference($params['transactionReference'])) {
            $errors[] = 'Invalid transaction reference format';
        }
        
        if (!isset($params['customerMSISDN']) || !self::validateMSISDN($params['customerMSISDN'])) {
            $errors[] = 'Invalid customer MSISDN format (should be 258XXXXXXXX)';
        }
        
        if (!isset($params['amount']) || !self::validateAmount($params['amount'])) {
            $errors[] = 'Invalid amount (should be positive number)';
        }
        
        if (!isset($params['thirdPartyReference']) || !self::validateThirdPartyReference($params['thirdPartyReference'])) {
            $errors[] = 'Invalid third party reference format';
        }
        
        if (!isset($params['serviceProviderCode']) || !self::validateServiceProviderCode($params['serviceProviderCode'])) {
            $errors[] = 'Invalid service provider code format (should be 6 digits)';
        }
        
        return $errors;
    }
    
    /**
     * Validate all required parameters for B2C transaction
     */
    public static function validateB2CParameters(array $params): array
    {
        return self::validateC2BParameters($params); // Same validation rules
    }
    
    /**
     * Validate all required parameters for B2B transaction
     */
    public static function validateB2BParameters(array $params): array
    {
        $errors = [];
        
        if (!isset($params['transactionReference']) || !self::validateTransactionReference($params['transactionReference'])) {
            $errors[] = 'Invalid transaction reference format';
        }
        
        if (!isset($params['amount']) || !self::validateAmount($params['amount'])) {
            $errors[] = 'Invalid amount (should be positive number)';
        }
        
        if (!isset($params['thirdPartyReference']) || !self::validateThirdPartyReference($params['thirdPartyReference'])) {
            $errors[] = 'Invalid third party reference format';
        }
        
        if (!isset($params['primaryPartyCode']) || !self::validateServiceProviderCode($params['primaryPartyCode'])) {
            $errors[] = 'Invalid primary party code format (should be 6 digits)';
        }
        
        if (!isset($params['receiverPartyCode']) || !self::validateServiceProviderCode($params['receiverPartyCode'])) {
            $errors[] = 'Invalid receiver party code format (should be 6 digits)';
        }
        
        return $errors;
    }
    
    /**
     * Validate all required parameters for reversal transaction
     */
    public static function validateReversalParameters(array $params): array
    {
        $errors = [];
        
        if (!isset($params['transactionID']) || empty($params['transactionID'])) {
            $errors[] = 'Transaction ID is required';
        }
        
        if (!isset($params['securityCredential']) || !self::validateSecurityCredential($params['securityCredential'])) {
            $errors[] = 'Invalid security credential';
        }
        
        if (!isset($params['initiatorIdentifier']) || !self::validateInitiatorIdentifier($params['initiatorIdentifier'])) {
            $errors[] = 'Invalid initiator identifier';
        }
        
        if (!isset($params['thirdPartyReference']) || !self::validateThirdPartyReference($params['thirdPartyReference'])) {
            $errors[] = 'Invalid third party reference format';
        }
        
        if (isset($params['reversalAmount']) && !self::validateAmount($params['reversalAmount'])) {
            $errors[] = 'Invalid reversal amount (should be positive number)';
        }
        
        return $errors;
    }
}
