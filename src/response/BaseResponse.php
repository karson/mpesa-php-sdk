<?php

namespace Karson\MpesaPhpSdk\Response;

use Karson\MpesaPhpSdk\Constants\ResponseCodes;
use Psr\Http\Message\ResponseInterface;

abstract class BaseResponse
{
    protected int $statusCode;
    protected mixed $response;
    protected array $headers;
    
    public function __construct(ResponseInterface $response)
    {
        $this->statusCode = $response->getStatusCode();
        $bodyContents = $response->getBody()->getContents();
        $this->response = json_decode($bodyContents);
        
        if ($this->response === null) {
            $this->response = $bodyContents;
        }
        
        $this->headers = $response->getHeaders();
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
}
