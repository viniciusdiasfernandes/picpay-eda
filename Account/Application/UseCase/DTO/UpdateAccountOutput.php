<?php

namespace Account\Application\UseCase\DTO;

use Account\Domain\AccountType;

readonly class UpdateAccountOutput
{
    public function __construct(
        public string $accountId,
        public string $firstName,
        public string $lastName,
        public string $document,
        public string $email,
        public AccountType $type,
        public float $balance
    )
    {
    }
}