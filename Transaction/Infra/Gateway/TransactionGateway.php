<?php

namespace Transaction\Infra\Gateway;

use Transaction\Application\UseCases\DTO\ProcessTransactionInput;
use Transaction\Application\UseCases\DTO\TransactionReturnTrueOutput;

interface TransactionGateway
{
    public function process(ProcessTransactionInput $input): TransactionReturnTrueOutput;
}