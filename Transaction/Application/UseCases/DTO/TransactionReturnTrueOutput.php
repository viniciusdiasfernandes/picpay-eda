<?php

namespace Transaction\Application\UseCases\DTO;

class TransactionReturnTrueOutput
{
    public function __construct(readonly bool $success)
    {
    }
}