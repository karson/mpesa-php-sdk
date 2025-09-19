<?php

namespace Karson\MpesaPhpSdk\Exceptions;

class AuthenticationException extends MpesaException
{
    public function __construct(string $message = "Authentication failed", int $code = 401, ?\Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
