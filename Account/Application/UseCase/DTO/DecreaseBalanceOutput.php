<?php

namespace Account\Application\UseCase\DTO;

class DecreaseBalanceOutput
{
    public function __construct(
        readonly int   $accountId,
        readonly float $balance
    )
    {
    }
}