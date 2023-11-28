<?php

namespace Transaction\Tests\unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Transaction\Domain\CancelledStatus;
use Transaction\Domain\Transaction;

class CancelledStatusTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testStart()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $cancelledStatus = new CancelledStatus($transaction);
        $this->expectException(Exception::class);
        $cancelledStatus->start();
    }

    public function testFinish()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $cancelledStatus = new CancelledStatus($transaction);
        $this->expectException(Exception::class);
        $cancelledStatus->finish();
    }

    public function testCancel()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $cancelledStatus = new CancelledStatus($transaction);
        $this->expectException(Exception::class);
        $cancelledStatus->cancel();
    }
}