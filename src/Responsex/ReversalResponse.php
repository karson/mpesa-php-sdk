<?php

namespace Karson\MpesaPhpSdk\Response;

use Karson\MpesaPhpSdk\Response\BaseResponse;

class ReversalResponse extends BaseResponse
{
    private ?string $output_TransactionID;
    private ?string $output_ConversationID;
    private ?string $output_OriginatorConversationID;
    private ?string $output_ResponseCode;
    private ?string $output_ResponseDesc;
    private ?string $output_ReversalTransactionID;
    private ?float $output_ReversalAmount;
    
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        parent::__construct($response);
        $this->parseReversalResponse();
    }
    
    private function parseReversalResponse(): void
    {
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        
        if ($data) {
            $this->output_TransactionID = $data->output_TransactionID ?? null;
            $this->output_ConversationID = $data->output_ConversationID ?? null;
            $this->output_OriginatorConversationID = $data->output_OriginatorConversationID ?? null;
            $this->output_ResponseCode = $data->output_ResponseCode ?? null;
            $this->output_ResponseDesc = $data->output_ResponseDesc ?? null;
            $this->output_ReversalTransactionID = $data->output_ReversalTransactionID ?? null;
            $this->output_ReversalAmount = isset($data->output_ReversalAmount) ? (float)$data->output_ReversalAmount : null;
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
    
    public function getOriginatorConversationId(): ?string
    {
        return $this->output_OriginatorConversationID;
    }
    
    public function getResponseCode(): ?string
    {
        return $this->output_ResponseCode;
    }
    
    public function getResponseDescription(): ?string
    {
        return $this->output_ResponseDesc;
    }
    
    public function getReversalTransactionId(): ?string
    {
        return $this->output_ReversalTransactionID;
    }
    
    public function getReversalAmount(): ?float
    {
        return $this->output_ReversalAmount;
    }
    
    public function isReversalSuccessful(): bool
    {
        return $this->output_ResponseCode === 'INS-0' && !empty($this->output_ReversalTransactionID);
    }
    
    public function isPartialReversal(): bool
    {
        return $this->output_ReversalAmount !== null;
    }
}
