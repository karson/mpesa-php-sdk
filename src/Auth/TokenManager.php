<?php

namespace Karson\MpesaPhpSdk\Auth;

class TokenManager
{
    private ?string $token = null;
    private string $publicKey;
    private string $apiKey;
    
    public function __construct(string $publicKey, string $apiKey)
    {
        $this->publicKey = $publicKey;
        $this->apiKey = $apiKey;
    }
    
    /**
     * Get current token, generating a new one if needed
     */
    public function getToken(): string
    {
        if ($this->token === null) {
            $this->generateToken();
        }
        
        return $this->token;
    }
    
    /**
     * Generate a new authentication token
     */
    private function generateToken(): void
    {
        $key = "-----BEGIN PUBLIC KEY-----\n";
        $key .= wordwrap($this->publicKey, 60, "\n", true);
        $key .= "\n-----END PUBLIC KEY-----";
        
        $encrypted = '';
        openssl_public_encrypt($this->apiKey, $encrypted, $key, OPENSSL_PKCS1_PADDING);
        
        $this->token = base64_encode($encrypted);
    }
    
    /**
     * Clear stored token
     */
    public function clearToken(): void
    {
        $this->token = null;
    }
}
