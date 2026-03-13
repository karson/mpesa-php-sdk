<?php

namespace Tests\Unit\Response;

use PHPUnit\Framework\TestCase;
use Karson\MpesaPhpSdk\Response\CustomerNameResponse;
use GuzzleHttp\Psr7\Response;

class CustomerNameResponseTest extends TestCase
{
    private function createMockResponse(int $statusCode, array $body): Response
    {
        return new Response($statusCode, [], json_encode($body));
    }

    public function testSuccessfulCustomerNameLookup()
    {
        $responseData = [
            'output_CustomerMSISDN' => '258841234567',
            'output_FirstName' => 'João',
            'output_SecondName' => 'Silva',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Request processed successfully'
        ];

        $response = new CustomerNameResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isCustomerFound());
        $this->assertEquals('258841234567', $response->getCustomerMSISDN());
        $this->assertEquals('João', $response->getFirstName());
        $this->assertEquals('Silva', $response->getSecondName());
        $this->assertEquals('Silva', $response->getLastName());
        $this->assertEquals('João Silva', $response->getCustomerName());
    }

    public function testCustomerWithOnlyFirstName()
    {
        $responseData = [
            'output_CustomerMSISDN' => '258841234567',
            'output_FirstName' => 'Maria',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Request processed successfully'
        ];

        $response = new CustomerNameResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isCustomerFound());
        $this->assertEquals('Maria', $response->getFirstName());
        $this->assertNull($response->getSecondName());
        $this->assertEquals('Maria', $response->getCustomerName());
    }

    public function testCustomerNotFound()
    {
        $responseData = [
            'output_CustomerMSISDN' => '258841234567',
            'output_ResponseCode' => 'INS-4',
            'output_ResponseDescription' => 'Customer not found'
        ];

        $response = new CustomerNameResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isCustomerFound());
        $this->assertNull($response->getFirstName());
        $this->assertNull($response->getSecondName());
        $this->assertNull($response->getCustomerName());
    }

    public function testInvalidMSISDN()
    {
        $responseData = [
            'output_ResponseCode' => 'INS-4',
            'output_ResponseDescription' => 'Invalid MSISDN format'
        ];

        $response = new CustomerNameResponse($this->createMockResponse(200, $responseData));

        $this->assertFalse($response->isCustomerFound());
        $this->assertNull($response->getCustomerMSISDN());
        $this->assertEquals('INS-4', $response->getResponseCode());
    }

    public function testCustomerNameWithSpecialCharacters()
    {
        $responseData = [
            'output_CustomerMSISDN' => '258841234567',
            'output_FirstName' => 'José',
            'output_SecondName' => "N'Golo",
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Success'
        ];

        $response = new CustomerNameResponse($this->createMockResponse(200, $responseData));

        $this->assertTrue($response->isCustomerFound());
        $this->assertEquals('José', $response->getFirstName());
        $this->assertEquals("N'Golo", $response->getSecondName());
        $this->assertEquals("José N'Golo", $response->getCustomerName());
    }

    public function testEmptyNames()
    {
        $responseData = [
            'output_CustomerMSISDN' => '258841234567',
            'output_FirstName' => '',
            'output_SecondName' => '',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Success'
        ];

        $response = new CustomerNameResponse($this->createMockResponse(200, $responseData));

        $this->assertFalse($response->isCustomerFound());
        $this->assertNull($response->getCustomerName());
    }

    public function testHttpErrorOnCustomerLookup()
    {
        $response = new CustomerNameResponse($this->createMockResponse(500, ['error' => 'Server error']));

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isCustomerFound());
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testUnauthorizedLookup()
    {
        $response = new CustomerNameResponse($this->createMockResponse(401, ['error' => 'Unauthorized']));

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testInheritsBaseResponseMethods()
    {
        $responseData = [
            'output_TransactionID' => 'CUST-TXN-001',
            'output_ConversationID' => 'CUST-CONV-001',
            'output_ResponseCode' => 'INS-0',
            'output_ResponseDescription' => 'Success',
            'output_CustomerMSISDN' => '258841234567',
            'output_FirstName' => 'Test'
        ];

        $response = new CustomerNameResponse($this->createMockResponse(200, $responseData));

        $this->assertEquals('CUST-TXN-001', $response->getTransactionId());
        $this->assertEquals('CUST-CONV-001', $response->getConversationId());
        $this->assertTrue($response->isApiSuccess());
        $this->assertTrue($response->isTransactionSuccessful());
    }
}
