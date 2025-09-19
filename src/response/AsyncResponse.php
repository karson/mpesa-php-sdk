<?php

namespace Karson\MpesaPhpSdk\Response;

abstract class AsyncResponse extends BaseResponse
{
    protected ?string $output_ThirdPartyReference = null;
    protected ?string $output_ConversationID = null;
    protected ?string $output_ResponseCode = null;
    protected ?string $output_ResponseDesc = null;
    
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        parent::__construct($response);
        $this->parseResponse();
    }
    
    protected function parseResponse(): void
    {
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        
        if ($data) {
            $this->output_ThirdPartyReference = $data->output_ThirdPartyReference ?? null;
            $this->output_ConversationID = $data->output_ConversationID ?? null;
            $this->output_ResponseCode = $data->output_ResponseCode ?? null;
            $this->output_ResponseDesc = $data->output_ResponseDesc ?? null;
        }
    }
    
    public function getThirdPartyReference(): ?string
    {
        return $this->output_ThirdPartyReference;
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
        return $this->output_ResponseDesc;
    }
    
    /**
     * Para resposta assíncrona, o status da transação deve ser verificado
     * posteriormente usando o ConversationID através do método status()
     */
    public function isTransactionInitiated(): bool
    {
        return $this->isSuccessful() && !empty($this->output_ConversationID);
    }
    
    /**
     * Retorna true se a transação foi aceita para processamento assíncrono
     */
    public function isAcceptedForProcessing(): bool
    {
        // Códigos de resposta comuns para aceitação assíncrona
        $acceptedCodes = ['INS-0', '0', 'PENDING'];
        return in_array($this->output_ResponseCode, $acceptedCodes);
    }
}
