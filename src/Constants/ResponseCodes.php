<?php

namespace Karson\MpesaPhpSdk\Constants;

class ResponseCodes
{
    // Success codes
    public const SUCCESS = 'INS-0';
    
    // Error codes
    public const INTERNAL_ERROR = 'INS-1';
    public const INSUFFICIENT_BALANCE = 'INS-2';
    public const TRANSACTION_FAILED = 'INS-4';
    public const TRANSACTION_EXPIRED = 'INS-5';
    public const TRANSACTION_NOT_PERMITTED = 'INS-6';
    public const REQUEST_TIMEOUT = 'INS-9';
    public const DUPLICATE_TRANSACTION = 'INS-10';
    
    // Success status codes
    public const HTTP_SUCCESS = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_ACCEPTED = 202;
    
    // Error status codes
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_INTERNAL_ERROR = 500;
    
    /**
     * Check if response code indicates success
     */
    public static function isSuccess(string $code): bool
    {
        return $code === self::SUCCESS;
    }
    
    /**
     * Get human readable description for response code
     */
    public static function getDescription(string $code): string
    {
        return match($code) {
            self::SUCCESS => 'Request processed successfully',
            self::INTERNAL_ERROR => 'Internal Error',
            self::INSUFFICIENT_BALANCE => 'Not enough balance',
            self::TRANSACTION_FAILED => 'Transaction failed',
            self::TRANSACTION_EXPIRED => 'Transaction expired',
            self::TRANSACTION_NOT_PERMITTED => 'Transaction not permitted to sender',
            self::REQUEST_TIMEOUT => 'Request timeout',
            self::DUPLICATE_TRANSACTION => 'Duplicate transaction',
            default => 'Unknown response code'
        };
    }
    
    /**
     * Check if HTTP status code indicates success
     */
    public static function isHttpSuccess(int $statusCode): bool
    {
        return $statusCode >= 200 && $statusCode < 300;
    }
}
