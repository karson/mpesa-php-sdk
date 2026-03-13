<?php

namespace Tests\Unit\Response;

use PHPUnit\Framework\TestCase;
use Karson\MpesaPhpSdk\Response\TransactionStatusResponse;
use Karson\MpesaPhpSdk\Constants\TransactionStatus;
use GuzzleHttp\Psr7\Response;

class TransactionStatusResponseTest extends TestCase
{
    private function createMockResponse(int $statusCode, array $body): Response
    {
        return new Response($statusCode, [], json_encode($body));
    }

    public function testCompletedTransactionStatus()
    {
        $responseData = [
            'output_ConversationID' => 'CONV-001',
            'output_ResponseTransactionStatus' => 'Completed',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Request processed successfully',
            'output_ThirdPartyReference' => 'REF-001',
            'output_TransactionID' => 'TXN-001'
        ];

        $response = new TransactionStatusResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isTransactionCompleted());
        $this->assertFalse($response->isTransactionPending());
        $this->assertFalse($response->isTransactionFailed());
        $this->assertEquals('Completed', $response->getTransactionStatus());
        $this->assertEquals('CONV-001', $response->getConversationId());
        $this->assertEquals('INS-0', $response->getResponseCode());
    }

    public function testPendingTransactionStatus()
    {
        $responseData = [
            'output_ConversationID' => 'CONV-002',
            'output_ResponseTransactionStatus' => 'Pending',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Transaction pending'
        ];

        $response = new TransactionStatusResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isTransactionPending());
        $this->assertFalse($response->isTransactionCompleted());
        $this->assertFalse($response->isTransactionFailed());
        $this->assertEquals('Pending', $response->getTransactionStatus());
    }

    public function testFailedTransactionStatus()
    {
        $responseData = [
            'output_ConversationID' => 'CONV-003',
            'output_ResponseTransactionStatus' => 'Failed',
            'output_ResponseCode' => 'INS-4',
            'output_ResponseDescription' => 'Transaction failed'
        ];

        $response = new TransactionStatusResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isTransactionFailed());
        $this->assertFalse($response->isTransactionCompleted());
        $this->assertFalse($response->isTransactionPending());
        $this->assertEquals('Failed', $response->getTransactionStatus());
    }

    public function testCancelledTransactionStatus()
    {
        $responseData = [
            'output_ConversationID' => 'CONV-004',
            'output_ResponseTransactionStatus' => 'Cancelled',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Transaction cancelled by user'
        ];

        $response = new TransactionStatusResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isTransactionFailed());
        $this->assertFalse($response->isTransactionCompleted());
        $this->assertEquals('Cancelled', $response->getTransactionStatus());
    }

    public function testProcessingTransactionStatus()
    {
        $responseData = [
            'output_ConversationID' => 'CONV-005',
            'output_ResponseTransactionStatus' => 'Processing',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Transaction processing'
        ];

        $response = new TransactionStatusResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isTransactionPending());
        $this->assertFalse($response->isTransactionCompleted());
        $this->assertEquals('Processing', $response->getTransactionStatus());
    }

    public function testTransactionNotFoundError()
    {
        $responseData = [
            'output_ResponseCode' => 'INS-4',
            'output_ResponseDescription' => 'Transaction not found'
        ];

        $response = new TransactionStatusResponse($this->createMockResponse(200, $responseData));

        $this->assertNull($response->getTransactionStatus());
        $this->assertFalse($response->isTransactionCompleted());
        $this->assertEquals('INS-4', $response->getResponseCode());
    }

    public function testNullTransactionStatus()
    {
        $responseData = [
            'output_ConversationID' => 'CONV-006',
            'output_ResponseCode' => 'INS-0'
        ];

        $response = new TransactionStatusResponse($this->createMockResponse(200, $responseData));

        $this->assertNull($response->getTransactionStatus());
        $this->assertFalse($response->isTransactionCompleted());
        $this->assertFalse($response->isTransactionPending());
        $this->assertFalse($response->isTransactionFailed());
    }

    public function testHttpErrorOnStatusCheck()
    {
        $response = new TransactionStatusResponse($this->createMockResponse(500, ['error' => 'Server error']));

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testInheritsBaseResponseMethods()
    {
        $responseData = [
            'output_TransactionID' => 'TXN-STATUS-001',
            'output_ConversationID' => 'CONV-STATUS-001',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Success',
            'output_ThirdPartyReference' => 'TPR-001',
            'output_ResponseTransactionStatus' => 'Completed'
        ];

        $response = new TransactionStatusResponse($this->createMockResponse(200, $responseData));

        $this->assertEquals('TXN-STATUS-001', $response->getTransactionId());
        $this->assertEquals('TPR-001', $response->getThirdPartyReference());
        $this->assertTrue($response->isApiSuccess());
    }
}
