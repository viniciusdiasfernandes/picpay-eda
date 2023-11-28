<?php

namespace Account\Application\UseCase\DTO;

readonly class GetAccountOutput
{
    public function __construct(
        public int    $accountId,
        public string $name,
        public string $lastName,
        public string $document,
        public string $email,
        public string $type,
        public string $balance,
    )
    {
    }
}