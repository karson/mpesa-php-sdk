<?php

namespace Karson\MpesaPhpSdk\Response;

use Karson\MpesaPhpSdk\Response\BaseResponse;
use Karson\MpesaPhpSdk\Constants\TransactionStatus;

class TransactionStatusResponse extends BaseResponse
{
    private ?string $output_TransactionID;
    private ?string $output_ConversationID;
    private ?string $output_TransactionStatus;
    private ?string $output_ResponseCode;
    private ?string $output_ResponseDesc;
    private ?float $output_Amount;
    private ?string $output_Currency;
    private ?string $output_ReceiverParty;
    private ?string $output_TransactionCompletedDateTime;
    
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        parent::__construct($response);
        $this->parseStatusResponse();
    }
    
    private function parseStatusResponse(): void
    {
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        
        if ($data) {
            $this->output_TransactionID = $data->output_TransactionID ?? null;
            $this->output_ConversationID = $data->output_ConversationID ?? null;
            $this->output_TransactionStatus = $data->output_TransactionStatus ?? null;
            $this->output_ResponseCode = $data->output_ResponseCode ?? null;
            $this->output_ResponseDesc = $data->output_ResponseDesc ?? null;
            $this->output_Amount = isset($data->output_Amount) ? (float)$data->output_Amount : null;
            $this->output_Currency = $data->output_Currency ?? null;
            $this->output_ReceiverParty = $data->output_ReceiverParty ?? null;
            $this->output_TransactionCompletedDateTime = $data->output_TransactionCompletedDateTime ?? null;
        }
    }
    
    public function getTransactionId(): ?string
    {
        return $this->output_TransactionID;
    }
    
    public function getConversationId(): ?string
    {
        return $this->output_ConversationID;
    }
    
    public function getTransactionStatus(): ?string
    {
        return $this->output_TransactionStatus;
    }
    
    public function getResponseCode(): ?string
    {
        return $this->output_ResponseCode;
    }
    
    public function getResponseDescription(): ?string
    {
        return $this->output_ResponseDesc;
    }
    
    public function getAmount(): ?float
    {
        return $this->output_Amount;
    }
    
    public function getCurrency(): ?string
    {
        return $this->output_Currency;
    }
    
    public function getReceiverParty(): ?string
    {
        return $this->output_ReceiverParty;
    }
    
    public function getTransactionCompletedDateTime(): ?string
    {
        return $this->output_TransactionCompletedDateTime;
    }
    
    public function isTransactionCompleted(): bool
    {
        return TransactionStatus::isCompleted($this->output_TransactionStatus ?? '');
    }
    
    public function isTransactionPending(): bool
    {
        return TransactionStatus::isPending($this->output_TransactionStatus ?? '');
    }
    
    public function isTransactionFailed(): bool
    {
        return TransactionStatus::isFailed($this->output_TransactionStatus ?? '');
    }
}
