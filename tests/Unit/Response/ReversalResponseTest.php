<?php

namespace Tests\Unit\Response;

use PHPUnit\Framework\TestCase;
use Karson\MpesaPhpSdk\Response\ReversalResponse;
use GuzzleHttp\Psr7\Response;

class ReversalResponseTest extends TestCase
{
    private function createMockResponse(int $statusCode, array $body): Response
    {
        return new Response($statusCode, [], json_encode($body));
    }

    public function testSuccessfulFullReversal()
    {
        $responseData = [
            'output_TransactionID' => 'ORIG-TXN-001',
            'output_ConversationID' => 'REV-CONV-001',
            'output_OriginatorConversationID' => 'ORIG-CONV-001',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Request processed successfully',
            'output_ReversalTransactionID' => 'REV-TXN-001'
        ];

        $response = new ReversalResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isReversalSuccessful());
        $this->assertFalse($response->isPartialReversal());
        $this->assertEquals('ORIG-TXN-001', $response->getTransactionId());
        $this->assertEquals('REV-CONV-001', $response->getConversationId());
        $this->assertEquals('ORIG-CONV-001', $response->getOriginatorConversationId());
        $this->assertEquals('REV-TXN-001', $response->getReversalTransactionId());
        $this->assertNull($response->getReversalAmount());
    }

    public function testSuccessfulPartialReversal()
    {
        $responseData = [
            'output_TransactionID' => 'ORIG-TXN-002',
            'output_ConversationID' => 'REV-CONV-002',
            'output_OriginatorConversationID' => 'ORIG-CONV-002',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Partial reversal processed',
            'output_ReversalTransactionID' => 'REV-TXN-002',
            'output_ReversalAmount' => 500.00
        ];

        $response = new ReversalResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isReversalSuccessful());
        $this->assertTrue($response->isPartialReversal());
        $this->assertEquals(500.00, $response->getReversalAmount());
        $this->assertEquals('REV-TXN-002', $response->getReversalTransactionId());
    }

    public function testReversalFailedTransactionNotFound()
    {
        $responseData = [
            'output_ResponseCode' => 'INS-4',
            'output_ResponseDescription' => 'Original transaction not found'
        ];

        $response = new ReversalResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isReversalSuccessful());
        $this->assertNull($response->getReversalTransactionId());
        $this->assertEquals('INS-4', $response->getResponseCode());
    }

    public function testReversalFailedAlreadyReversed()
    {
        $responseData = [
            'output_TransactionID' => 'ORIG-TXN-003',
            'output_ResponseCode' => 'INS-10',
            'output_ResponseDescription' => 'Transaction already reversed'
        ];

        $response = new ReversalResponse($this->createMockResponse(200, $responseData));

        $this->assertFalse($response->isReversalSuccessful());
        $this->assertEquals('INS-10', $response->getResponseCode());
    }

    public function testReversalFailedInsufficientBalance()
    {
        $responseData = [
            'output_TransactionID' => 'ORIG-TXN-004',
            'output_ResponseCode' => 'INS-2',
            'output_ResponseDescription' => 'Insufficient balance for reversal'
        ];

        $response = new ReversalResponse($this->createMockResponse(200, $responseData));

        $this->assertFalse($response->isReversalSuccessful());
        $this->assertEquals('INS-2', $response->getResponseCode());
    }

    public function testReversalWithDecimalAmount()
    {
        $responseData = [
            'output_TransactionID' => 'ORIG-TXN-005',
            'output_ConversationID' => 'REV-CONV-005',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Partial reversal processed',
            'output_ReversalTransactionID' => 'REV-TXN-005',
            'output_ReversalAmount' => 123.45
        ];

        $response = new ReversalResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isPartialReversal());
        $this->assertEquals(123.45, $response->getReversalAmount());
        $this->assertIsFloat($response->getReversalAmount());
    }

    public function testReversalWithStringAmount()
    {
        $responseData = [
            'output_TransactionID' => 'ORIG-TXN-006',
            'output_ResponseCode' => 'INS-0',
            'output_ReversalTransactionID' => 'REV-TXN-006',
            'output_ReversalAmount' => '250.50'
        ];

        $response = new ReversalResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isPartialReversal());
        $this->assertEquals(250.50, $response->getReversalAmount());
        $this->assertIsFloat($response->getReversalAmount());
    }

    public function testHttpErrorOnReversal()
    {
        $response = new ReversalResponse($this->createMockResponse(500, ['error' => 'Server error']));

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isReversalSuccessful());
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testUnauthorizedReversal()
    {
        $response = new ReversalResponse($this->createMockResponse(401, ['error' => 'Unauthorized']));

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testReversalTimeout()
    {
        $responseData = [
            'output_ResponseCode' => 'INS-9',
            'output_ResponseDescription' => 'Request timeout'
        ];

        $response = new ReversalResponse($this->createMockResponse(200, $responseData));

        $this->assertFalse($response->isReversalSuccessful());
        $this->assertEquals('INS-9', $response->getResponseCode());
    }

    public function testInheritsBaseResponseMethods()
    {
        $responseData = [
            'output_TransactionID' => 'REV-BASE-001',
            'output_ConversationID' => 'REV-CONV-BASE',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Success',
            'output_ThirdPartyReference' => 'TPR-REV-001',
            'output_ReversalTransactionID' => 'REV-TXN-BASE'
        ];

        $response = new ReversalResponse($this->createMockResponse(200, $responseData));

        $this->assertEquals('REV-BASE-001', $response->getTransactionId());
        $this->assertEquals('REV-CONV-BASE', $response->getConversationId());
        $this->assertEquals('TPR-REV-001', $response->getThirdPartyReference());
        $this->assertTrue($response->isApiSuccess());
        $this->assertTrue($response->isTransactionSuccessful());
    }
}
