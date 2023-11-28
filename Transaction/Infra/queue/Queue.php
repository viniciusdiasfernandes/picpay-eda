<?php

namespace Transaction\Infra\queue;

interface Queue
{
    public function publish(string $exchange, array $input): void;
    public function consume(string $exchange, callable $callback): void;
}