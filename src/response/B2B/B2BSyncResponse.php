<?php

namespace Karson\MpesaPhpSdk\Response\B2B;

use Karson\MpesaPhpSdk\Response\SyncResponse;

class B2BSyncResponse extends SyncResponse
{
    private ?string $output_ThirdPartyReference;
    
    protected function parseResponse(): void
    {
        parent::parseResponse();
        
        $data = is_object($this->response) ? $this->response : json_decode($this->response);
        if ($data) {
            $this->output_ThirdPartyReference = $data->output_ThirdPartyReference ?? null;
        }
    }
    
    public function getThirdPartyReference(): ?string
    {
        return $this->output_ThirdPartyReference;
    }
}
