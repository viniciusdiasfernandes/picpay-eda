<?php

namespace Account\Application\UseCase\DTO;

class IncreaseBalanceOutput
{
    public function __construct(
        readonly int   $accountId,
        readonly float $balance
    )
    {
    }
}