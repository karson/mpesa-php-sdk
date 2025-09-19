<?php

namespace Karson\MpesaPhpSdk\Exceptions;

class ValidationException extends MpesaException
{
    private array $errors = [];
    
    public function __construct(array $errors, string $message = "Validation failed", int $code = 422)
    {
        $this->errors = $errors;
        
        if (empty($message) && !empty($errors)) {
            $message = "Validation failed: " . implode(', ', $errors);
        }
        
        parent::__construct($message, $code, null, ['errors' => $errors]);
    }
    
    /**
     * Get validation errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * Get first validation error
     */
    public function getFirstError(): ?string
    {
        return $this->errors[0] ?? null;
    }
    
    /**
     * Check if has specific error
     */
    public function hasError(string $error): bool
    {
        return in_array($error, $this->errors);
    }
}
