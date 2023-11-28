<?php

namespace Transaction\Tests\integration;

use PHPUnit\Framework\TestCase;
use Transaction\Infra\Controller\QueueController;
use Transaction\Infra\queue\RabbitMQAdapter;

class QueueControllerTest extends TestCase
{
    public function testConsume()
    {
        $rabbitMQ = new RabbitMQAdapter();
        new QueueController($rabbitMQ);
    }
}