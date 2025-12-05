<?php

namespace Karson\MpesaPhpSdk\Response;

use Karson\MpesaPhpSdk\Response\BaseResponse;
use Karson\MpesaPhpSdk\Constants\TransactionStatus;

class TransactionStatusResponse extends BaseResponse
{
    private ?string $output_ConversationID;
    private ?string $output_ResponseTransactionStatus;
    private ?string $output_ResponseCode;
    private ?string $output_ResponseDesc;
    private ?string $output_ThirdPartyReference;
    
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        parent::__construct($response);
        $this->parseStatusResponse();
    }
    
    private function parseStatusResponse(): void
    {
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        
        if ($data) {
            $this->output_ConversationID = $data->output_ConversationID ?? null;
            $this->output_ResponseTransactionStatus = $data->output_ResponseTransactionStatus ?? null;
            $this->output_ResponseCode = $data->output_ResponseCode ?? null;
            $this->output_ResponseDesc = $data->output_ResponseDesc ?? null;
            $this->output_ThirdPartyReference = $data->output_ThirdPartyReference ?? null;
        }
    }
    
    public function getConversationId(): ?string
    {
        return $this->output_ConversationID;
    }
    
    public function getTransactionStatus(): ?string
    {
        return $this->output_ResponseTransactionStatus;
    }
    
    public function getResponseCode(): ?string
    {
        return $this->output_ResponseCode;
    }
    
    public function getResponseDescription(): ?string
    {
        return $this->output_ResponseDesc;
    }
    
    public function getThirdPartyReference(): ?string
    {
        return $this->output_ThirdPartyReference;
    }
    
    public function isTransactionCompleted(): bool
    {
        return TransactionStatus::isCompleted($this->output_ResponseTransactionStatus ?? '');
    }
    
    public function isTransactionPending(): bool
    {
        return TransactionStatus::isPending($this->output_ResponseTransactionStatus ?? '');
    }
    
    public function isTransactionFailed(): bool
    {
        return TransactionStatus::isFailed($this->output_ResponseTransactionStatus ?? '');
    }
}
