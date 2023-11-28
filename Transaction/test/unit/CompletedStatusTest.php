<?php

namespace Transaction\Tests\unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Transaction\Domain\CompletedStatus;
use Transaction\Domain\Transaction;

class CompletedStatusTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testStart()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $cancelledStatus = new CompletedStatus($transaction);
        $this->expectException(Exception::class);
        $cancelledStatus->start();
    }

    public function testFinish()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $cancelledStatus = new CompletedStatus($transaction);
        $this->expectException(Exception::class);
        $cancelledStatus->finish();
    }

    public function testCancel()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $cancelledStatus = new CompletedStatus($transaction);
        $this->expectException(Exception::class);
        $cancelledStatus->cancel();
    }
}