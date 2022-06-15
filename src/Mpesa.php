<?php

namespace Karson\MpesaPhpSdk;

use GuzzleHttp\Client;

class Mpesa
{
    private $base_uri = 'https://api.sandbox.vm.co.mz';
    private $public_key;
    private $api_key;
    private $service_provider_code;

    public function __construct($config = null)
    {
        if (is_array($config)) {
            $this->setPublicKey($config['public_key']);
            $this->setApiKey($config['api_key']);
            $this->setEnv($config['env']);
            $this->setServiceProviderCode($config['service_provider_code']);
        }
    }

    public function setPublicKey($public_key)
    {
        $this->public_key = trim($public_key);
    }

    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
    }

    public function setServiceProviderCode($service_provider_code)
    {
        $this->service_provider_code = $service_provider_code;
    }

    public function setEnv($env)
    {
        if ($env == 'live') {
            $this->base_uri = 'https://api.vm.co.mz';
        }
    }


    /**
     * Standard customer-to-business transaction
     *
     * @param  $transactionReference
     * @param  $customerMSISDN
     * @param  $amount
     * @param  $thirdPartReferece
     * @param  $serviceProviderCode
     * @return \stdClass
     */
    public function c2b($transactionReference, $customerMSISDN, $amount, $thirdPartReferece, $serviceProviderCode = null)
    {
        $serviceProviderCode = $serviceProviderCode ?: $this->service_provider_code;
        $fields = [
            "input_TransactionReference" => $transactionReference,
            "input_CustomerMSISDN" => $customerMSISDN,
            "input_Amount" => $amount,
            "input_ThirdPartyReference" => $thirdPartReferece,
            "input_ServiceProviderCode" => $serviceProviderCode
        ];

        return $this->makeRequest('/ipg/v1x/c2bPayment/singleStage/', 18352, 'POST', $fields);
    }

    /**
     * Business to customer transaction
     *
     * @param [type] $transactionReference
     * @param [type] $customerMSISDN
     * @param [type] $amount
     * @param [type] $thirdPartReferece
     * @param [type] $serviceProviderCode
     * @return stdClass
     */
    public function b2c($transactionReference, $customerMSISDN, $amount, $thirdPartReferece, $serviceProviderCode = null)
    {
        $serviceProviderCode = $serviceProviderCode ?: $this->service_provider_code;
        $fields = [
            "input_TransactionReference" => $transactionReference,
            "input_CustomerMSISDN" => $customerMSISDN,
            "input_Amount" => $amount,
            "input_ThirdPartyReference" => $thirdPartReferece,
            "input_ServiceProviderCode" => $serviceProviderCode
        ];

        return $this->makeRequest('/ipg/v1x/b2cPayment/', 18345, 'POST', $fields);
    }

    /**
     * @param $transactionID
     * @param $securityCredential
     * @param $initiatorIdentifier
     * @param $thirdPartyReference
     * @param $serviceProviderCode
     * @param $reversalAmount
     * @return \stdClass
     */
    public function transactionReversal($transactionID, $securityCredential, $initiatorIdentifier, $thirdPartyReference, $serviceProviderCode = null, $reversalAmount = null)
    {
        $serviceProviderCode = $serviceProviderCode ?: $this->service_provider_code;
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
        return $this->makeRequest('/ipg/v1x/reversal/', 18354, 'PUT', $fields);
    }

    /**
     * @param $thirdPartyReference
     * @param $queryReference
     * @param $serviceProviderCode
     * @return \stdClass
     */
    public function status($thirdPartyReference, $queryReference, $serviceProviderCode = null)
    {
        $serviceProviderCode = $serviceProviderCode ?: $this->service_provider_code;
        $fields = [
            'input_ThirdPartyReference' => $thirdPartyReference,
            'input_QueryReference' => $queryReference,
            'input_ServiceProviderCode' => $serviceProviderCode
        ];



        return $this->makeRequest('/ipg/v1x/queryTransactionStatus/', 18353, 'GET', $fields);
    }

    /**
     * The Query Customer Name API is used to provide the customer’s name associated to the mobile money wallet on Business to Wallet transfers, for confirmation purposes. This API will provide the First and Second name from the customer but obfuscated.
     * @param $thirdPartyReference
     * @param $queryReference
     * @param $serviceProviderCode
     * @return \stdClass
     */
    public function queryCustomerName($customerMSISDN, $thirdPartReferece, $serviceProviderCode = null)
    {
        $serviceProviderCode = $serviceProviderCode ?: $this->service_provider_code;
        $fields = [
            "input_CustomerMSISDN" => $customerMSISDN,
            "input_ThirdPartyReference" => $thirdPartReferece,
            "input_ServiceProviderCode" => $serviceProviderCode
        ];

        return $this->makeRequest('/ipg/v1x/queryCustomerName/', 19323, 'GET', $fields);
    }

    /**
     * Generates a base64 encoded token
     */
    public function getToken()
    {
        if (!empty($this->public_key) && !empty($this->api_key)) {
            $key = "-----BEGIN PUBLIC KEY-----\n";
            $key .= wordwrap($this->public_key, 60, "\n", true);
            $key .= "\n-----END PUBLIC KEY-----";
            $pk = openssl_get_publickey($key);
            openssl_public_encrypt($this->api_key, $token, $pk, OPENSSL_PKCS1_PADDING);

            return base64_encode($token);
        }
        return 'error';
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $fields
     * @return \stdClass
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

        $response = $client->request($method, $url, $options);

        $return = new \stdClass();
        $return->response = json_decode($response->getBody());

        if ($return->response == false) {
            $return->response = $response->getBody();
        }


        $return->status = $response->getStatusCode();
        return $return;
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
            'Connection' => 'keep-alive'
        ];
        return $headers;
    }
}
