<?php

namespace Karson\MpesaPhpSdk\Response\B2B;

use Karson\MpesaPhpSdk\Response\BaseResponse;

class B2BResponseFactory
{
    /**
     * Cria a response apropriada baseada no tipo de requisição B2B
     * 
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param bool $isAsync Se true, retorna B2BAsyncResponse, senão B2BSyncResponse
     * @return BaseResponse
     */
    public static function create(\Psr\Http\Message\ResponseInterface $response, bool $isAsync = false): BaseResponse
    {
        if ($isAsync) {
            return new B2BAsyncResponse($response);
        }
        
        return new B2BSyncResponse($response);
    }
    
}
