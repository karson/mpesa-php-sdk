<?php

namespace Tests\Unit\Validation;

use PHPUnit\Framework\TestCase;
use Karson\MpesaPhpSdk\Validation\ParameterValidator;

class ParameterValidatorTest extends TestCase
{
    public function testValidateMSISDNReturnsTrueForValidNumbers()
    {
        $this->assertTrue(ParameterValidator::validateMSISDN('258841234567'));
        $this->assertTrue(ParameterValidator::validateMSISDN('258851234567'));
        $this->assertTrue(ParameterValidator::validateMSISDN('258891234567'));
        $this->assertTrue(ParameterValidator::validateMSISDN('258801234567'));
    }
    
    public function testValidateMSISDNReturnsFalseForInvalidNumbers()
    {
        $this->assertFalse(ParameterValidator::validateMSISDN('25884123456')); // Too short
        $this->assertFalse(ParameterValidator::validateMSISDN('2588412345678')); // Too long
        $this->assertFalse(ParameterValidator::validateMSISDN('258741234567')); // Wrong prefix
        $this->assertFalse(ParameterValidator::validateMSISDN('258641234567')); // Wrong prefix
        $this->assertFalse(ParameterValidator::validateMSISDN('123841234567')); // Wrong country code
        $this->assertFalse(ParameterValidator::validateMSISDN('25884123456a')); // Contains letter
        $this->assertFalse(ParameterValidator::validateMSISDN('')); // Empty
    }
    
    public function testValidateTransactionReferenceReturnsTrueForValidReferences()
    {
        $this->assertTrue(ParameterValidator::validateTransactionReference('TXN001'));
        $this->assertTrue(ParameterValidator::validateTransactionReference('ABC123'));
        $this->assertTrue(ParameterValidator::validateTransactionReference('123456'));
        $this->assertTrue(ParameterValidator::validateTransactionReference('T'));
        $this->assertTrue(ParameterValidator::validateTransactionReference(str_repeat('A', 50)));
    }
    
    public function testValidateTransactionReferenceReturnsFalseForInvalidReferences()
    {
        $this->assertFalse(ParameterValidator::validateTransactionReference('')); // Empty
        $this->assertFalse(ParameterValidator::validateTransactionReference('TXN-001')); // Contains hyphen
        $this->assertFalse(ParameterValidator::validateTransactionReference('TXN 001')); // Contains space
        $this->assertFalse(ParameterValidator::validateTransactionReference(str_repeat('A', 51))); // Too long
    }
    
    public function testValidateServiceProviderCodeReturnsTrueForValidCodes()
    {
        $this->assertTrue(ParameterValidator::validateServiceProviderCode('171717'));
        $this->assertTrue(ParameterValidator::validateServiceProviderCode('123456'));
        $this->assertTrue(ParameterValidator::validateServiceProviderCode('000000'));
        $this->assertTrue(ParameterValidator::validateServiceProviderCode('999999'));
    }
    
    public function testValidateServiceProviderCodeReturnsFalseForInvalidCodes()
    {
        $this->assertFalse(ParameterValidator::validateServiceProviderCode('17171')); // Too short
        $this->assertFalse(ParameterValidator::validateServiceProviderCode('1717171')); // Too long
        $this->assertFalse(ParameterValidator::validateServiceProviderCode('17171a')); // Contains letter
        $this->assertFalse(ParameterValidator::validateServiceProviderCode('')); // Empty
        $this->assertFalse(ParameterValidator::validateServiceProviderCode('171-717')); // Contains hyphen
    }
    
    public function testValidateAmountReturnsTrueForValidAmounts()
    {
        $this->assertTrue(ParameterValidator::validateAmount(100));
        $this->assertTrue(ParameterValidator::validateAmount(100.50));
        $this->assertTrue(ParameterValidator::validateAmount('100'));
        $this->assertTrue(ParameterValidator::validateAmount('100.50'));
        $this->assertTrue(ParameterValidator::validateAmount(0.01));
    }
    
    public function testValidateAmountReturnsFalseForInvalidAmounts()
    {
        $this->assertFalse(ParameterValidator::validateAmount(0));
        $this->assertFalse(ParameterValidator::validateAmount(-100));
        $this->assertFalse(ParameterValidator::validateAmount(''));
        $this->assertFalse(ParameterValidator::validateAmount('abc'));
        $this->assertFalse(ParameterValidator::validateAmount(null));
    }
    
    public function testValidateC2BParametersReturnsEmptyArrayForValidParams()
    {
        $params = [
            'transactionReference' => 'TXN001',
            'customerMSISDN' => '258841234567',
            'amount' => 100,
            'thirdPartyReference' => 'REF001',
            'serviceProviderCode' => '171717'
        ];
        
        $errors = ParameterValidator::validateC2BParameters($params);
        $this->assertEmpty($errors);
    }
    
    public function testValidateC2BParametersReturnsErrorsForInvalidParams()
    {
        $params = [
            'transactionReference' => '', // Invalid
            'customerMSISDN' => '123456789', // Invalid
            'amount' => -100, // Invalid
            'thirdPartyReference' => 'REF-001', // Invalid
            'serviceProviderCode' => '12345' // Invalid
        ];
        
        $errors = ParameterValidator::validateC2BParameters($params);
        $this->assertNotEmpty($errors);
        $this->assertCount(5, $errors);
    }
    
    public function testValidateB2BParametersReturnsEmptyArrayForValidParams()
    {
        $params = [
            'transactionReference' => 'TXN001',
            'amount' => 100,
            'thirdPartyReference' => 'REF001',
            'primaryPartyCode' => '171717',
            'receiverPartyCode' => '979797'
        ];
        
        $errors = ParameterValidator::validateB2BParameters($params);
        $this->assertEmpty($errors);
    }
    
    public function testValidateReversalParametersReturnsEmptyArrayForValidParams()
    {
        $params = [
            'transactionID' => 'TXN123456',
            'securityCredential' => 'valid_credential',
            'initiatorIdentifier' => 'valid_initiator',
            'thirdPartyReference' => 'REF001',
            'reversalAmount' => 50
        ];
        
        $errors = ParameterValidator::validateReversalParameters($params);
        $this->assertEmpty($errors);
    }
}
