<?php

namespace Karson\MpesaPhpSdk;

use GuzzleHttp\Client;
use function GuzzleHttp\json_decode;

class Mpesa
{

    private $base_uri = 'https://api.sandbox.vm.co.mz';
    private $public_key;
    private $api_key;

    public function __construct($config = null)
    {
        if (is_array($config)) {
            $this->setPublicKey($config['public_key']);
            $this->setApiKey($config['api_key']);
            $this->setEnv($config['env']);
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

    public function setEnv($env)
    {
        if ($env == 'live') {
            $this->base_uri = 'https://api.vm.co.mz';
        }
    }


    /* Standard customer-to-business transaction
     *
     * @param string $transactionReference This is the reference of the transaction for the customer or business making the * transaction. This can be a smartcard number for a TV subscription or a reference number of a utility bill.
     * @param string $customerMSISDN  MSISDN of the customer for the transaction
     * @param string $amount The amount for the transaction.
     * @param string $thirdPartReferece This is the unique reference of the third party system. When there are queries about transactions, this will usually be used to track a transaction.
     * @param string $serviceCode Shortcode of the business where funds will be credited to.
     * @return \stdClass
     */
    function c2b($transactionReference, $customerMSISDN, $amount, $thirdPartReferece, $serviceCode)
    {

        $fields = [
            "input_TransactionReference" => $transactionReference,
            "input_CustomerMSISDN" => $customerMSISDN,
            "input_Amount" => $amount,
            "input_ThirdPartyReference" => $thirdPartReferece,
            "input_ServiceProviderCode" => $serviceCode
        ];

        return $this->makeRequest('/ipg/v1x/c2bPayment/singleStage/', 18352,  'POST', $fields);
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
    public function transactionReversal($transactionID, $securityCredential, $initiatorIdentifier, $thirdPartyReference, $serviceProviderCode, $reversalAmount)
    {
        $fields = [
            "input_TransactionID" => $transactionID,
            "input_SecurityCredential" => $securityCredential,
            "input_InitiatorIdentifier" => $initiatorIdentifier,
            "input_ThirdPartyReference" => $thirdPartyReference,
            "input_ServiceProviderCode" => $serviceProviderCode,
            "input_ReversalAmount" => $reversalAmount
        ];
        return $this->makeRequest('/ipg/v1x/reversal/', 18354, 'POST', $fields);
    }

    /**
     * @param $thirdPartyReference
     * @param $queryReference
     * @param $serviceProviderCode
     * @return \stdClass
     */
    public function status($thirdPartyReference, $queryReference, $serviceProviderCode)
    {

        $fields = [
            'input_ThirdPartyReference' => $thirdPartyReference,
            'input_QueryReference' => $queryReference,
            'input_ServiceProviderCode' => $serviceProviderCode
        ];



        return $this->makeRequest('/ipg/v1x/queryTransactionStatus/', 18353, 'GET', $fields);
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

        if ($method == 'POST') {
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
