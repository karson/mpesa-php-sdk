<?php

namespace Karson\MpesaPhpSdk\Response;

use Karson\MpesaPhpSdk\Constants\ResponseCodes;
use Psr\Http\Message\ResponseInterface;

abstract class BaseResponse
{
    protected int $statusCode;
    protected mixed $response;
    protected array $headers;
    

    protected ?string $output_TransactionID = null; // Sync response property
    protected ?string $output_ConversationID = null;
    protected ?string $output_ResponseCode = null;
    protected ?string $output_ResponseDescription = null;
    private ?string $output_ThirdPartyReference = null; // Sync response property

    
    
    public function __construct(ResponseInterface $response)
    {
        $this->statusCode = $response->getStatusCode();
        $bodyContents = $response->getBody()->getContents();
        $this->response = json_decode($bodyContents);
        
        if ($this->response === null) {
            $this->response = $bodyContents;
        }
        
        $this->headers = $response->getHeaders();
        $this->parseResponse();
    }
    
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    public function getRawResponse(): mixed
    {
        return $this->response;
    }
    
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    public function isSuccessful(): bool
    {
        return ResponseCodes::isHttpSuccess($this->statusCode);
    }
    
    /**
     * Check if the API response code indicates success
     */
    public function isApiSuccess(): bool
    {
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        
        if ($data && isset($data->output_ResponseCode)) {
            return ResponseCodes::isSuccess($data->output_ResponseCode);
        }
        
        return false;
    }
    
    /**
     * Get the API response code description
     */
    public function getApiResponseDescription(): string
    {
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        
        if ($data && isset($data->output_ResponseCode)) {
            return ResponseCodes::getDescription($data->output_ResponseCode);
        }
        
        return 'Unknown response';
    }
    
    /**
     * Parse the response data and extract sync response properties
     */
    protected function parseResponse(): void
    {
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        
        if ($data) {
            $this->output_TransactionID = $data->output_TransactionID ?? null;
            $this->output_ConversationID = $data->output_ConversationID ?? null;
            $this->output_ResponseCode = $data->output_ResponseCode ?? null;
            $this->output_ResponseDescription = $data->output_ResponseDescription ?? $data->output_ResponseDesc ?? null;
            $this->output_ThirdPartyReference = $data->output_ThirdPartyReference ?? null;
        }
    }
    
    /**
     * Get the transaction ID
     */
    public function getTransactionId(): ?string
    {
        return $this->output_TransactionID;
    }
    
    /**
     * Get the conversation ID
     */
    public function getConversationId(): ?string
    {
        return $this->output_ConversationID;
    }
    
    /**
     * Get the response code
     */
    public function getResponseCode(): ?string
    {
        return $this->output_ResponseCode;
    }
    
    /**
     * Get the response description
     */
    public function getResponseDescription(): ?string
    {
        return $this->output_ResponseDescription;
    }
    
    /**
     * Checks if the transaction was successful based on HTTP and API response codes
     */
    public function isTransactionSuccessful(): bool
    {
        return $this->isSuccessful() && $this->isApiSuccess();
    }

    /**
     * For asynchronous responses, the transaction status should be checked
     * later using the ConversationID through the status() method
     */
    public function isTransactionInitiated(): bool
    {
        return $this->isSuccessful() && !empty($this->output_ConversationID);
    }

    /**
     * Get the third party reference
     */
    public function getThirdPartyReference(): ?string
    {
        return $this->output_ThirdPartyReference;
    }
}
