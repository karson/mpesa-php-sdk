<?php

namespace Karson\MpesaPhpSdk\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Karson\MpesaPhpSdk\Response\TransactionResponse c2b(string $transactionReference, string $customerMSISDN, float $amount, string $thirdPartyReference, ?string $serviceProviderCode = null)
 * @method static \Karson\MpesaPhpSdk\Response\TransactionResponse b2c(string $customerMSISDN, int $amount, string $transactionReference, string $thirdPartyReference, ?string $serviceProviderCode = null)
 * @method static \Karson\MpesaPhpSdk\Response\TransactionResponse b2b(string $transactionReference, int $amount, string $thirdPartyReference, string $primaryPartyCode, string $receiverPartyCode)
 * @method static \Karson\MpesaPhpSdk\Response\ReversalResponse reversal(string $transactionID, string $securityCredential, string $initiatorIdentifier, string $thirdPartyReference, ?string $serviceProviderCode = null, ?string $reversalAmount = null)
 * @method static \Karson\MpesaPhpSdk\Response\TransactionStatusResponse queryTransactionStatus(string $thirdPartyReference, string $queryReference, ?string $serviceProviderCode = null)
 * @method static \Karson\MpesaPhpSdk\Response\CustomerNameResponse queryCustomerName(string $customerMSISDN, string $thirdPartyReference, ?string $serviceProviderCode = null)
 * @method static string getToken()
 * @method static \Karson\MpesaPhpSdk\Auth\TokenManager getTokenManager()
 * 
 * @see \Karson\MpesaPhpSdk\Mpesa
 */
class Mpesa extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mpesa';
    }
}