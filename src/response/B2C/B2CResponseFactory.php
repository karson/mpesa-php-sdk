<?php

namespace Karson\MpesaPhpSdk\Response\B2C;

use Karson\MpesaPhpSdk\Response\BaseResponse;

class B2CResponseFactory
{
    /**
     * Cria a response apropriada baseada no tipo de requisição B2C
     * 
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param bool $isAsync Se true, retorna B2CAsyncResponse, senão B2CSyncResponse
     * @return BaseResponse
     */
    public static function create(\Psr\Http\Message\ResponseInterface $response, bool $isAsync = false): BaseResponse
    {
        if ($isAsync) {
            return new B2CAsyncResponse($response);
        }
        
        return new B2CSyncResponse($response);
    }
    
}
