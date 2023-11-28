<?php

namespace Transaction\Tests\integration;

use PHPUnit\Framework\TestCase;
use Transaction\Domain\Transaction;
use Transaction\Infra\queue\RabbitMQAdapter;

class RabbitMQAdapterTest extends TestCase
{
    public function testPublish()
    {
        try {
            $queue = new RabbitMQAdapter();
            $transaction = Transaction::restore(10, 1, 1,123,'in_progress',1);
            $status = $transaction->status->value;
            $transaction = (array)$transaction;
            $transaction['status'] = $status;
            $queue->publish('transactionCreated', $transaction);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}