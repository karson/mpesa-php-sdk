<?php

namespace Karson\MpesaPhpSdk\Constants;

class TransactionStatus
{
    // Transaction status values
    public const COMPLETED = 'Completed';
    public const PENDING = 'Pending';
    public const CANCELLED = 'Cancelled';
    public const EXPIRED = 'Expired';
    public const FAILED = 'Failed';
    public const NOT_AVAILABLE = 'N/A';
    
    // Alternative status values (sometimes returned by API)
    public const SUCCESS = 'Success';
    public const SUCCESSFUL = 'Successful';
    public const PROCESSING = 'Processing';
    public const REJECTED = 'Rejected';
    
    /**
     * Check if transaction status indicates completion
     */
    public static function isCompleted(string $status): bool
    {
        $completedStatuses = [
            self::COMPLETED,
            self::SUCCESS,
            self::SUCCESSFUL
        ];
        
        return in_array(strtoupper($status), array_map('strtoupper', $completedStatuses));
    }
    
    /**
     * Check if transaction status indicates pending state
     */
    public static function isPending(string $status): bool
    {
        $pendingStatuses = [
            self::PENDING,
            self::PROCESSING
        ];
        
        return in_array(strtoupper($status), array_map('strtoupper', $pendingStatuses));
    }
    
    /**
     * Check if transaction status indicates failure
     */
    public static function isFailed(string $status): bool
    {
        $failedStatuses = [
            self::FAILED,
            self::CANCELLED,
            self::REJECTED,
            self::EXPIRED
        ];
        
        return in_array(strtoupper($status), array_map('strtoupper', $failedStatuses));
    }
    
    /**
     * Get all possible status values
     */
    public static function getAllStatuses(): array
    {
        return [
            self::COMPLETED,
            self::PENDING,
            self::CANCELLED,
            self::EXPIRED,
            self::FAILED,
            self::NOT_AVAILABLE,
            self::SUCCESS,
            self::SUCCESSFUL,
            self::PROCESSING,
            self::REJECTED
        ];
    }
}
