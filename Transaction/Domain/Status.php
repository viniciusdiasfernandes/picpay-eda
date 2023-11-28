<?php

namespace Transaction\Domain;

abstract class Status
{
    public function __construct(public Transaction $transaction)
    {
    }
    public abstract function start(): void;
    public abstract function finish(): void;
    public abstract function cancel(): void;
}