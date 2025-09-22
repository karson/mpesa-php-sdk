<?php

namespace Karson\MpesaPhpSdk;

use GuzzleHttp\Client;
use Karson\MpesaPhpSdk\Auth\TokenManager;
use Karson\MpesaPhpSdk\Response\ReversalResponse;
use Karson\MpesaPhpSdk\Response\TransactionResponse;
use Karson\MpesaPhpSdk\Validation\ParameterValidator;
use Karson\MpesaPhpSdk\Exceptions\ValidationException;
use Karson\MpesaPhpSdk\Exceptions\AuthenticationException;
use Karson\MpesaPhpSdk\Response\TransactionStatusResponse;
use Karson\MpesaPhpSdk\Response\CustomerNameResponse;

class Mpesa
{
    private string $base_uri = 'https://api.sandbox.vm.co.mz';
    private ?TokenManager $tokenManager = null;

    
    /**
     * Class constructor
     *
     * @param string $publicKey The public key provided by M-Pesa
     * @param string $apiKey The API key provided by M-Pesa
     * @param bool $isTest Whether the sandbox environment should be used
     * @param string $providerCode The service provider code
     */
    public function __construct(private string $publicKey, private string $apiKey, private bool $isTest = true, private ?string $serviceProviderCode = null)
    {
        $this->tokenManager = new TokenManager($publicKey, $apiKey);

        if (!$isTest) {
            $this->base_uri = 'https://api.vm.co.mz';
        }
    }


    /**
     * Standard customer-to-business transaction
     *
     * @param string $transactionReference
     * @param string $from
     * @param int $amount
     * @param string $thirdPartReference
     * @return TransactionResponse
     * @throws ValidationException
     */
    public function c2b(string $transactionReference, string $from, float $amount, string $thirdPartReference, ?string $serviceProviderCode = null): TransactionResponse
    {
        // Validate parameters
        $params = [
            'transactionReference' => $transactionReference,
            'customerMSISDN' => $from,
            'amount' => $amount,
            'thirdPartyReference' => $thirdPartReference,
            'serviceProviderCode' => $serviceProviderCode ?? $this->serviceProviderCode
        ];
        
        $errors = ParameterValidator::validateC2BParameters($params);
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        
        $fields = [
            "input_TransactionReference" => $transactionReference,
            "input_CustomerMSISDN" => $from,
            "input_Amount" => $amount,
            "input_ThirdPartyReference" => $thirdPartReference,
            "input_ServiceProviderCode" => $serviceProviderCode ?? $this->serviceProviderCode
        ];

        $response = $this->makeRequest('/ipg/v1x/c2bPayment/singleStage/', 18352, 'POST', $fields);
        
        return new TransactionResponse($response);
    }


    /**
     * Business to customer transaction sync
     *
     * @param string $customerMSISDN
     * @param int $amount
     * @param string $transactionReference
     * @param string $thirdPartReference
     * @return TransactionResponse
     */
    public function b2c(string $customerMSISDN, int $amount, string $transactionReference, string $thirdPartReference, ?string $serviceProviderCode = "171717"): TransactionResponse
    {
        $fields = [
            "input_TransactionReference" => $transactionReference,
            "input_CustomerMSISDN" => $customerMSISDN,
            "input_Amount" => $amount,
            "input_ThirdPartyReference" => $thirdPartReference,
            "input_ServiceProviderCode" => $serviceProviderCode ?? $this->serviceProviderCode
        ];

        $response = $this->makeRequest('/ipg/v1x/b2cPayment/', 18345, 'POST', $fields);
        
        return new TransactionResponse($response);
    }

  

    /**
     * Business to business transaction sync
     *
     * @param string $transactionReference
     * @param int $amount
     * @param string $thirdPartReference
     * @param string $primaryPartyCode Business shortcode for funds debit
     * @param string $receiverPartyCode Business shortcode for funds credit
     * @return TransactionResponse
     */
    public function b2b(string $transactionReference, int $amount, string $thirdPartReference, string $primaryPartyCode, string $receiverPartyCode): TransactionResponse
    {
        $fields = [
            "input_TransactionReference" => $transactionReference,
            "input_Amount" => $amount,
            "input_ThirdPartyReference" => $thirdPartReference,
            "input_PrimaryPartyCode" => $primaryPartyCode,
            "input_ReceiverPartyCode" => $receiverPartyCode
        ];

        $response = $this->makeRequest('/ipg/v1x/b2bPayment/', 18349, 'POST', $fields);
        
        return new TransactionResponse($response);
    }



    /**
     * Process transaction refund/reversal
     *
     * @param string $transactionID
     * @param string $securityCredential
     * @param string $initiatorIdentifier
     * @param string $thirdPartyReference
     * @param string $serviceProviderCode
     * @param string $reversalAmount Optional: for partial refunds
     * @return ReversalResponse
     */
    public function reversal(string $transactionID, string $securityCredential, string $initiatorIdentifier, string $thirdPartyReference, ?string $serviceProviderCode = null, ?string $reversalAmount = null): ReversalResponse
    {
        $serviceProviderCode = $serviceProviderCode ?: $this->serviceProviderCode;
        $fields = [
            "input_TransactionID" => $transactionID,
            "input_SecurityCredential" => $securityCredential,
            "input_InitiatorIdentifier" => $initiatorIdentifier,
            "input_ThirdPartyReference" => $thirdPartyReference,
            "input_ServiceProviderCode" => $serviceProviderCode,
        ];
        if (isset($reversalAmount)) {
            $fields['input_ReversalAmount'] = $reversalAmount;
        }
        
        $response = $this->makeRequest('/ipg/v1x/reversal/', 18354, 'PUT', $fields);
        
        return new ReversalResponse($response);
    }

    /**
     * Query transaction status
     *
     * @param string $thirdPartyReference
     * @param string $queryReference
     * @param string $serviceProviderCode
     * @return TransactionStatusResponse
     */
    public function queryTransactionStatus(string $thirdPartyReference, string $queryReference, ?string $serviceProviderCode = null): TransactionStatusResponse
    {
        $serviceProviderCode = $serviceProviderCode ?: $this->serviceProviderCode;
        $fields = [
            'input_ThirdPartyReference' => $thirdPartyReference,
            'input_QueryReference' => $queryReference,
            'input_ServiceProviderCode' => $serviceProviderCode
        ];

        $response = $this->makeRequest('/ipg/v1x/queryTransactionStatus/', 18353, 'GET', $fields);
        
        return new TransactionStatusResponse($response);
    }

    /**
     * Query customer name for confirmation purposes
     * The API provides the First and Second name from the customer but obfuscated.
     * 
     * @param string $customerMSISDN
     * @param string $thirdPartyReference
     * @param string $serviceProviderCode
     * @return CustomerNameResponse
     */
    public function queryCustomerName(string $customerMSISDN, string $thirdPartyReference, ?string $serviceProviderCode = null): CustomerNameResponse
    {
        $serviceProviderCode = $serviceProviderCode ?: $this->serviceProviderCode;
        $fields = [
            "input_CustomerMSISDN" => $customerMSISDN,
            "input_ThirdPartyReference" => $thirdPartyReference,
            "input_ServiceProviderCode" => $serviceProviderCode
        ];

        $response = $this->makeRequest('/ipg/v1x/queryCustomerName/', 19323, 'GET', $fields);
        
        return new CustomerNameResponse($response);
    }

    /**
     * Generates a base64 encoded token
     * @throws AuthenticationException
     */
    public function getToken(): string
    {
        if (empty($this->publicKey) || empty($this->apiKey)) {
            throw new AuthenticationException('Public key and API key are required');
        }
        
        try {
            return $this->tokenManager->getToken();
        } catch (\Exception $e) {
            throw new AuthenticationException('Failed to generate token: ' . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Get token manager instance
     */
    public function getTokenManager(): TokenManager
    {
        return $this->tokenManager;
    }

    /**
     * @param string $url
     * @param int $port
     * @param string $method
     * @param array $fields
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function makeRequest(string $url, int $port, string $method, array $fields = [])
    {
        $client = new Client([
            'base_uri' => $this->base_uri . ':' . $port,
            'timeout' => 90,
        ]);

        $options = [
            'http_errors' => false,
            'headers' => $this->getHeaders(),
            'verify' => false
        ];

        if ($method == 'POST' || $method == 'PUT') {
            $options +=  ['json' => $fields];
        } else {
            $options += ['query' => $fields];
        }
        return $client->request($method, $url, $options);
    }

    /**
     * @return array
     */
    private function getHeaders()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' =>  'Bearer ' . $this->getToken(),
            'origin' => 'developer.mpesa.vm.co.mz',
            'Connection' => 'keep-alive',
            'User-Agent' => 'Hypertech/MpesaPHP-SDK'
        ];
        return $headers;
    }
}
