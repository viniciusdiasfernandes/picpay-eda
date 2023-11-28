<?php

require 'vendor/autoload.php';

use Transaction\Infra\Controller\QueueController;
use Transaction\Infra\queue\RabbitMQAdapter;

$rabbitMQAdapter = new RabbitMQAdapter();
$queueController = new QueueController($rabbitMQAdapter);
$queueController->consumeEmail();
