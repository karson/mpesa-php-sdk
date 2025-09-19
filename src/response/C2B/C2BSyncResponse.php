<?php

namespace Karson\MpesaPhpSdk\Response\C2B;

use Karson\MpesaPhpSdk\Response\SyncResponse;

class C2BSyncResponse extends SyncResponse
{
    private ?string $output_OriginatorConversationID;
    
    protected function parseResponse(): void
    {
        parent::parseResponse();
        
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        if ($data) {
            $this->output_OriginatorConversationID = $data->output_OriginatorConversationID ?? null;
        }
    }
    
    public function getOriginatorConversationId(): ?string
    {
        return $this->output_OriginatorConversationID;
    }
}
