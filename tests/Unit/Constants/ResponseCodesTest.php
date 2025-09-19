<?php

namespace Tests\Unit\Constants;

use PHPUnit\Framework\TestCase;
use Karson\MpesaPhpSdk\Constants\ResponseCodes;

class ResponseCodesTest extends TestCase
{
    public function testIsSuccessReturnsTrueForSuccessCode()
    {
        $this->assertTrue(ResponseCodes::isSuccess(ResponseCodes::SUCCESS));
        $this->assertTrue(ResponseCodes::isSuccess('INS-0'));
    }
    
    public function testIsSuccessReturnsFalseForErrorCodes()
    {
        $this->assertFalse(ResponseCodes::isSuccess(ResponseCodes::INTERNAL_ERROR));
        $this->assertFalse(ResponseCodes::isSuccess(ResponseCodes::INSUFFICIENT_BALANCE));
        $this->assertFalse(ResponseCodes::isSuccess(ResponseCodes::TRANSACTION_FAILED));
        $this->assertFalse(ResponseCodes::isSuccess('UNKNOWN'));
    }
    
    public function testGetDescriptionReturnsCorrectDescriptions()
    {
        $this->assertEquals('Request processed successfully', ResponseCodes::getDescription(ResponseCodes::SUCCESS));
        $this->assertEquals('Internal Error', ResponseCodes::getDescription(ResponseCodes::INTERNAL_ERROR));
        $this->assertEquals('Not enough balance', ResponseCodes::getDescription(ResponseCodes::INSUFFICIENT_BALANCE));
        $this->assertEquals('Transaction failed', ResponseCodes::getDescription(ResponseCodes::TRANSACTION_FAILED));
        $this->assertEquals('Unknown response code', ResponseCodes::getDescription('UNKNOWN'));
    }
    
    public function testIsHttpSuccessReturnsTrueForSuccessStatusCodes()
    {
        $this->assertTrue(ResponseCodes::isHttpSuccess(200));
        $this->assertTrue(ResponseCodes::isHttpSuccess(201));
        $this->assertTrue(ResponseCodes::isHttpSuccess(202));
        $this->assertTrue(ResponseCodes::isHttpSuccess(299));
    }
    
    public function testIsHttpSuccessReturnsFalseForErrorStatusCodes()
    {
        $this->assertFalse(ResponseCodes::isHttpSuccess(400));
        $this->assertFalse(ResponseCodes::isHttpSuccess(401));
        $this->assertFalse(ResponseCodes::isHttpSuccess(404));
        $this->assertFalse(ResponseCodes::isHttpSuccess(500));
        $this->assertFalse(ResponseCodes::isHttpSuccess(199));
        $this->assertFalse(ResponseCodes::isHttpSuccess(300));
    }
    
    public function testConstantsHaveCorrectValues()
    {
        $this->assertEquals('INS-0', ResponseCodes::SUCCESS);
        $this->assertEquals('INS-1', ResponseCodes::INTERNAL_ERROR);
        $this->assertEquals('INS-2', ResponseCodes::INSUFFICIENT_BALANCE);
        $this->assertEquals('INS-4', ResponseCodes::TRANSACTION_FAILED);
        $this->assertEquals('INS-5', ResponseCodes::TRANSACTION_EXPIRED);
        $this->assertEquals('INS-6', ResponseCodes::TRANSACTION_NOT_PERMITTED);
        $this->assertEquals('INS-9', ResponseCodes::REQUEST_TIMEOUT);
        $this->assertEquals('INS-10', ResponseCodes::DUPLICATE_TRANSACTION);
    }
}
