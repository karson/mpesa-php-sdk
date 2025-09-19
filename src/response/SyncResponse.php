<?php

namespace Karson\MpesaPhpSdk\Response;

abstract class SyncResponse extends BaseResponse
{
    protected ?string $output_TransactionID = null;
    protected ?string $output_ConversationID = null;
    protected ?string $output_ResponseCode = null;
    protected ?string $output_ResponseDescription = null;
    
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        parent::__construct($response);
        $this->parseResponse();
    }
    
    protected function parseResponse(): void
    {
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        
        if ($data) {
            $this->output_TransactionID = $data->output_TransactionID ?? null;
            $this->output_ConversationID = $data->output_ConversationID ?? null;
            $this->output_ResponseCode = $data->output_ResponseCode ?? null;
            $this->output_ResponseDescription = $data->output_ResponseDescription ?? $data->output_ResponseDesc ?? null;
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
    
    public function getResponseCode(): ?string
    {
        return $this->output_ResponseCode;
    }
    
    public function getResponseDescription(): ?string
    {
        return $this->output_ResponseDescription;
    }
    
    /**
     * Verifica se a transação foi bem-sucedida baseado no código de resposta
     */
    public function isTransactionSuccessful(): bool
    {
        return $this->isSuccessful() && $this->isApiSuccess();
    }
}
