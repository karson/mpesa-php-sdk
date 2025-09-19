<?php

namespace Tests\Unit\Auth;

use PHPUnit\Framework\TestCase;
use Karson\MpesaPhpSdk\Auth\TokenManager;

class TokenManagerTest extends TestCase
{
    private TokenManager $tokenManager;
    
    protected function setUp(): void
    {
        // Generate a valid RSA key pair for testing
        $keyPair = openssl_pkey_new([
            'digest_alg' => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);
        
        $publicKeyDetails = openssl_pkey_get_details($keyPair);
        $publicKey = str_replace(['-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----', "\n"], '', $publicKeyDetails['key']);
        
        $this->tokenManager = new TokenManager($publicKey, 'test_api_key');
    }
    
    public function testGetTokenGeneratesNewToken()
    {
        $token = $this->tokenManager->getToken();
        $this->assertNotEmpty($token);
        $this->assertIsString($token);
    }
    
    public function testClearTokenResetsState()
    {
        $this->tokenManager->getToken();
        $this->tokenManager->clearToken();
        
        // After clearing, next call should generate new token
        $newToken = $this->tokenManager->getToken();
        $this->assertNotEmpty($newToken);
    }
    
    public function testTokenIsReusedWhenAlreadyGenerated()
    {
        $firstToken = $this->tokenManager->getToken();
        $secondToken = $this->tokenManager->getToken();
        
        $this->assertEquals($firstToken, $secondToken);
    }
}
