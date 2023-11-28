<?php

namespace Account\Application\UseCase\DTO;

class DecreaseBalanceInput
{
    public function __construct(
        readonly int   $accountId,
        readonly float $amount
    )
    {
    }
}