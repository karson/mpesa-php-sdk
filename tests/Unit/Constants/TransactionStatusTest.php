<?php

namespace Tests\Unit\Constants;

use PHPUnit\Framework\TestCase;
use Karson\MpesaPhpSdk\Constants\TransactionStatus;

class TransactionStatusTest extends TestCase
{
    public function testIsCompletedReturnsTrueForCompletedStatuses()
    {
        $this->assertTrue(TransactionStatus::isCompleted(TransactionStatus::COMPLETED));
        $this->assertTrue(TransactionStatus::isCompleted(TransactionStatus::SUCCESS));
        $this->assertTrue(TransactionStatus::isCompleted(TransactionStatus::SUCCESSFUL));
        $this->assertTrue(TransactionStatus::isCompleted('completed'));
        $this->assertTrue(TransactionStatus::isCompleted('SUCCESS'));
        $this->assertTrue(TransactionStatus::isCompleted('successful'));
    }
    
    public function testIsCompletedReturnsFalseForNonCompletedStatuses()
    {
        $this->assertFalse(TransactionStatus::isCompleted(TransactionStatus::PENDING));
        $this->assertFalse(TransactionStatus::isCompleted(TransactionStatus::FAILED));
        $this->assertFalse(TransactionStatus::isCompleted(TransactionStatus::CANCELLED));
        $this->assertFalse(TransactionStatus::isCompleted('UNKNOWN'));
    }
    
    public function testIsPendingReturnsTrueForPendingStatuses()
    {
        $this->assertTrue(TransactionStatus::isPending(TransactionStatus::PENDING));
        $this->assertTrue(TransactionStatus::isPending(TransactionStatus::PROCESSING));
        $this->assertTrue(TransactionStatus::isPending('pending'));
        $this->assertTrue(TransactionStatus::isPending('PROCESSING'));
    }
    
    public function testIsPendingReturnsFalseForNonPendingStatuses()
    {
        $this->assertFalse(TransactionStatus::isPending(TransactionStatus::COMPLETED));
        $this->assertFalse(TransactionStatus::isPending(TransactionStatus::FAILED));
        $this->assertFalse(TransactionStatus::isPending('UNKNOWN'));
    }
    
    public function testIsFailedReturnsTrueForFailedStatuses()
    {
        $this->assertTrue(TransactionStatus::isFailed(TransactionStatus::FAILED));
        $this->assertTrue(TransactionStatus::isFailed(TransactionStatus::CANCELLED));
        $this->assertTrue(TransactionStatus::isFailed(TransactionStatus::REJECTED));
        $this->assertTrue(TransactionStatus::isFailed(TransactionStatus::EXPIRED));
        $this->assertTrue(TransactionStatus::isFailed('failed'));
        $this->assertTrue(TransactionStatus::isFailed('CANCELLED'));
    }
    
    public function testIsFailedReturnsFalseForNonFailedStatuses()
    {
        $this->assertFalse(TransactionStatus::isFailed(TransactionStatus::COMPLETED));
        $this->assertFalse(TransactionStatus::isFailed(TransactionStatus::PENDING));
        $this->assertFalse(TransactionStatus::isFailed('UNKNOWN'));
    }
    
    public function testGetAllStatusesReturnsAllStatuses()
    {
        $allStatuses = TransactionStatus::getAllStatuses();
        
        $this->assertContains(TransactionStatus::COMPLETED, $allStatuses);
        $this->assertContains(TransactionStatus::PENDING, $allStatuses);
        $this->assertContains(TransactionStatus::CANCELLED, $allStatuses);
        $this->assertContains(TransactionStatus::EXPIRED, $allStatuses);
        $this->assertContains(TransactionStatus::FAILED, $allStatuses);
        $this->assertContains(TransactionStatus::NOT_AVAILABLE, $allStatuses);
        $this->assertContains(TransactionStatus::SUCCESS, $allStatuses);
        $this->assertContains(TransactionStatus::SUCCESSFUL, $allStatuses);
        $this->assertContains(TransactionStatus::PROCESSING, $allStatuses);
        $this->assertContains(TransactionStatus::REJECTED, $allStatuses);
        
        $this->assertCount(10, $allStatuses);
    }
    
    public function testConstantsHaveCorrectValues()
    {
        $this->assertEquals('Completed', TransactionStatus::COMPLETED);
        $this->assertEquals('Pending', TransactionStatus::PENDING);
        $this->assertEquals('Cancelled', TransactionStatus::CANCELLED);
        $this->assertEquals('Expired', TransactionStatus::EXPIRED);
        $this->assertEquals('Failed', TransactionStatus::FAILED);
        $this->assertEquals('N/A', TransactionStatus::NOT_AVAILABLE);
        $this->assertEquals('Success', TransactionStatus::SUCCESS);
        $this->assertEquals('Successful', TransactionStatus::SUCCESSFUL);
        $this->assertEquals('Processing', TransactionStatus::PROCESSING);
        $this->assertEquals('Rejected', TransactionStatus::REJECTED);
    }
}
