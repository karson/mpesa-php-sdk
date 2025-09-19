<?php

namespace Karson\MpesaPhpSdk\Response\C2B;

use Karson\MpesaPhpSdk\Response\BaseResponse;
use Karson\MpesaPhpSdk\Response\AsyncResponse;
use Karson\MpesaPhpSdk\Response\SyncResponse;

class C2BResponseFactory
{
    /**
     * Cria a response apropriada baseada no tipo de requisição C2B
     * 
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param bool $isAsync Se true, retorna C2BAsyncResponse, senão C2BSyncResponse
     * @return C2BAsyncResponse|C2BSyncResponse
     */
    public static function create(\Psr\Http\Message\ResponseInterface $response, bool $isAsync = false): AsyncResponse|SyncResponse
    {
        if ($isAsync) {
            return new C2BAsyncResponse($response);
        }
        
        return new C2BSyncResponse($response);
    }
    
}
