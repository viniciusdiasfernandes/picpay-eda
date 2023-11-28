<?php

namespace Transaction\Tests\unit;

use Exception;
use PHPUnit\Framework\TestCase;
use Transaction\Domain\StatusEnum;
use Transaction\Domain\StatusFactory;
use Transaction\Domain\Transaction;

class StatusFactoryTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testStart()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $status = StatusFactory::create($transaction, StatusEnum::InProgress);
        $this->assertEquals($status->value, StatusEnum::InProgress->value);
    }

    public function testFinish()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $status = StatusFactory::create($transaction, StatusEnum::Completed);
        $this->assertEquals($status->value, StatusEnum::Completed->value);
    }

    public function testCancel()
    {
        $transaction = Transaction::create(amount: 100,senderId: 1,receiverId: 2);
        $status = StatusFactory::create($transaction, StatusEnum::Cancelled);
        $this->assertEquals($status->value, StatusEnum::Cancelled->value);
    }
}