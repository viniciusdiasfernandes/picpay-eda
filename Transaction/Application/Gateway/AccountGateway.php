<?php

namespace Transaction\Application\Gateway;

interface AccountGateway
{
    public function getById(int $accountId);

    public function increaseBalance(int $accountId, float $amount): bool|string;

    public function decreaseBalance(int $accountId, float $amount): bool|string;
}