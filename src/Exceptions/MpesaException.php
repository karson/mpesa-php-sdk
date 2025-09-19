<?php

namespace Karson\MpesaPhpSdk\Exceptions;

use Exception;

class MpesaException extends Exception
{
    protected array $context = [];
    
    public function __construct(string $message = "", int $code = 0, ?Exception $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }
    
    /**
     * Get additional context information
     */
    public function getContext(): array
    {
        return $this->context;
    }
    
    /**
     * Set additional context information
     */
    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }
    
    /**
     * Add context information
     */
    public function addContext(string $key, mixed $value): self
    {
        $this->context[$key] = $value;
        return $this;
    }
}
