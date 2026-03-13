<?php

namespace Karson\MpesaPhpSdk\Response;

use Karson\MpesaPhpSdk\Response\BaseResponse;
use Karson\MpesaPhpSdk\Constants\TransactionStatus;

class TransactionStatusResponse extends BaseResponse
{
    private ?string $output_ResponseTransactionStatus;
    
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        parent::__construct($response);
        $this->parseStatusResponse();
    }
    
    private function parseStatusResponse(): void
    {
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        
        if ($data) {
            $this->output_ResponseTransactionStatus = $data->output_ResponseTransactionStatus ?? null;
        }
    }
    
    public function getTransactionStatus(): ?string
    {
        return $this->output_ResponseTransactionStatus;
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
