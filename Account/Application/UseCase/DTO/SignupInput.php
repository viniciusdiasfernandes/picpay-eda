<?php

namespace Account\Application\UseCase\DTO;

use Account\Domain\AccountType;

readonly class SignupInput
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $document,
        public string $email,
        public string $password,
        public AccountType $type
    )
    {
    }
}