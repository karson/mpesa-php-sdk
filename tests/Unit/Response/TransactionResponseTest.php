<?php

namespace Tests\Unit\Response;

use PHPUnit\Framework\TestCase;
use Karson\MpesaPhpSdk\Response\TransactionResponse;
use GuzzleHttp\Psr7\Response;

class TransactionResponseTest extends TestCase
{
    private function createMockResponse(int $statusCode, array $body): Response
    {
        return new Response($statusCode, [], json_encode($body));
    }

    public function testSuccessfulC2BTransaction()
    {
        $responseData = [
            'output_TransactionID' => 'TXN123456789',
            'output_ConversationID' => 'CONV987654321',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Request processed successfully',
            'output_ThirdPartyReference' => 'REF001'
        ];

        $response = new TransactionResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isApiSuccess());
        $this->assertTrue($response->isTransactionSuccessful());
        $this->assertEquals('TXN123456789', $response->getTransactionId());
        $this->assertEquals('CONV987654321', $response->getConversationId());
        $this->assertEquals('INS-0', $response->getResponseCode());
        $this->assertEquals('Request processed successfully', $response->getResponseDescription());
        $this->assertEquals('REF001', $response->getThirdPartyReference());
    }

    public function testSuccessfulB2BTransaction()
    {
        $responseData = [
            'output_TransactionID' => 'B2B-TXN-001',
            'output_ConversationID' => 'B2B-CONV-001',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Request processed successfully',
            'output_ThirdPartyReference' => 'B2B-REF-001'
        ];

        $response = new TransactionResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isTransactionSuccessful());
        $this->assertEquals('B2B-TXN-001', $response->getTransactionId());
        $this->assertEquals('B2B-CONV-001', $response->getConversationId());
    }

    public function testSuccessfulB2CTransaction()
    {
        $responseData = [
            'output_TransactionID' => 'B2C-TXN-001',
            'output_ConversationID' => 'B2C-CONV-001',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Request processed successfully',
            'output_ThirdPartyReference' => 'B2C-REF-001'
        ];

        $response = new TransactionResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isTransactionSuccessful());
        $this->assertEquals('B2C-TXN-001', $response->getTransactionId());
    }

    public function testAsyncTransactionInitiated()
    {
        $responseData = [
            'output_ConversationID' => 'ASYNC-CONV-001',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDesc' => 'Request accepted for processing'
        ];

        $response = new TransactionResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isTransactionInitiated());
        $this->assertEquals('ASYNC-CONV-001', $response->getConversationId());
        $this->assertNull($response->getTransactionId());
    }

    public function testInsufficientBalanceError()
    {
        $responseData = [
            'output_ResponseCode' => 'INS-2',
            'output_ResponseDescription' => 'Not enough balance'
        ];

        $response = new TransactionResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isApiSuccess());
        $this->assertFalse($response->isTransactionSuccessful());
        $this->assertEquals('INS-2', $response->getResponseCode());
    }

    public function testTransactionFailedError()
    {
        $responseData = [
            'output_ResponseCode' => 'INS-4',
            'output_ResponseDescription' => 'Transaction failed'
        ];

        $response = new TransactionResponse($this->createMockResponse(200, $responseData));

        $this->assertFalse($response->isTransactionSuccessful());
        $this->assertEquals('INS-4', $response->getResponseCode());
    }

    public function testDuplicateTransactionError()
    {
        $responseData = [
            'output_ResponseCode' => 'INS-10',
            'output_ResponseDescription' => 'Duplicate Transaction'
        ];

        $response = new TransactionResponse($this->createMockResponse(200, $responseData));

        $this->assertFalse($response->isApiSuccess());
        $this->assertEquals('INS-10', $response->getResponseCode());
    }

    public function testHttpErrorResponse()
    {
        $response = new TransactionResponse($this->createMockResponse(500, ['error' => 'Internal Server Error']));

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isTransactionSuccessful());
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testUnauthorizedResponse()
    {
        $response = new TransactionResponse($this->createMockResponse(401, ['error' => 'Unauthorized']));

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testGetRawResponse()
    {
        $responseData = [
            'output_TransactionID' => 'TXN001',
            'output_ResponseCode' => 'INS-0'
        ];

        $response = new TransactionResponse($this->createMockResponse(200, $responseData));

        $raw = $response->getRawResponse();
        $this->assertIsString($raw);
        $this->assertStringContainsString('TXN001', $raw);
    }

    public function testGetHeaders()
    {
        $httpResponse = new Response(200, ['Content-Type' => 'application/json'], json_encode(['test' => 'data']));
        $response = new TransactionResponse($httpResponse);

        $headers = $response->getHeaders();
        $this->assertIsArray($headers);
        $this->assertArrayHasKey('Content-Type', $headers);
    }
}
