<?php

namespace Account\Application\UseCase\DTO;

class IncreaseBalanceInput
{
    public function __construct(
        readonly int   $accountId,
        readonly float $amount
    )
    {
    }
}