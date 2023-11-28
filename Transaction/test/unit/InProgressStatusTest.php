<?php

namespace Transaction\Tests\unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Transaction\Domain\InProgressStatus;
use Transaction\Domain\StatusEnum;
use Transaction\Domain\Transaction;

class InProgressStatusTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testStart()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $cancelledStatus = new InProgressStatus($transaction);
        $this->expectException(Exception::class);
        $cancelledStatus->start();
    }

    /**
     * @throws Exception
     */
    public function testFinish()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $cancelledStatus = new InProgressStatus($transaction);
        $cancelledStatus->finish();
        $this->assertEquals($transaction->status->value, StatusEnum::Completed->value);
    }

    /**
     * @throws Exception
     */
    public function testCancel()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $cancelledStatus = new InProgressStatus($transaction);
        $cancelledStatus->cancel();
        $this->assertEquals($transaction->status->value, StatusEnum::Cancelled->value);
    }
}