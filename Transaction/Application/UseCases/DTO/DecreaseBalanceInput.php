<?php

namespace Transaction\Application\UseCases\DTO;

readonly class DecreaseBalanceInput
{
    public function __construct(
        public string $accountId,
        public float $amount
    )
    {
    }
}