<?php

namespace Karson\MpesaPhpSdk\Response\Query;

use Karson\MpesaPhpSdk\Response\BaseResponse;

class CustomerNameResponse extends BaseResponse
{
    private ?string $output_CustomerMSISDN;
    private ?string $output_FirstName;
    private ?string $output_SecondName;
    private ?string $output_ResponseCode;
    private ?string $output_ResponseDesc;
    
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        parent::__construct($response);
        $this->parseCustomerNameResponse();
    }
    
    private function parseCustomerNameResponse(): void
    {
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        
        if ($data) {
            $this->output_CustomerMSISDN = $data->output_CustomerMSISDN ?? null;
            $this->output_FirstName = $data->output_FirstName ?? null;
            $this->output_SecondName = $data->output_SecondName ?? null;
            $this->output_ResponseCode = $data->output_ResponseCode ?? null;
            $this->output_ResponseDesc = $data->output_ResponseDesc ?? null;
        }
    }
    
    public function getCustomerMSISDN(): ?string
    {
        return $this->output_CustomerMSISDN;
    }
    
    public function getFirstName(): ?string
    {
        return $this->output_FirstName;
    }
    
    public function getSecondName(): ?string
    {
        return $this->output_SecondName;
    }
    
    public function getLastName(): ?string
    {
        return $this->output_SecondName;
    }
    
    public function getCustomerName(): ?string
    {
        $firstName = $this->output_FirstName ?? '';
        $secondName = $this->output_SecondName ?? '';
        
        return trim($firstName . ' ' . $secondName) ?: null;
    }
    
    public function getResponseCode(): ?string
    {
        return $this->output_ResponseCode;
    }
    
    public function getResponseDescription(): ?string
    {
        return $this->output_ResponseDesc;
    }
    
    public function isCustomerFound(): bool
    {
        return $this->isSuccessful() && !empty($this->output_FirstName);
    }
}
