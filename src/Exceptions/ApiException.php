<?php

namespace Karson\MpesaPhpSdk\Exceptions;

class ApiException extends MpesaException
{
    private ?string $responseCode = null;
    private ?string $responseDescription = null;
    
    public function __construct(
        string $message = "API request failed", 
        int $code = 500, 
        ?\Exception $previous = null,
        ?string $responseCode = null,
        ?string $responseDescription = null
    ) {
        $this->responseCode = $responseCode;
        $this->responseDescription = $responseDescription;
        
        $context = [];
        if ($responseCode) {
            $context['response_code'] = $responseCode;
        }
        if ($responseDescription) {
            $context['response_description'] = $responseDescription;
        }
        
        parent::__construct($message, $code, $previous, $context);
    }
    
    /**
     * Get API response code
     */
    public function getResponseCode(): ?string
    {
        return $this->responseCode;
    }
    
    /**
     * Get API response description
     */
    public function getResponseDescription(): ?string
    {
        return $this->responseDescription;
    }
}
