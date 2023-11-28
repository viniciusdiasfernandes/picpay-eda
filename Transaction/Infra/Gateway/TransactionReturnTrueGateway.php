<?php

namespace Transaction\Infra\Gateway;

use Transaction\Application\UseCases\DTO\ProcessTransactionInput;
use Transaction\Application\UseCases\DTO\ProcessTransactionOutput;
use Transaction\Application\UseCases\DTO\TransactionReturnTrueOutput;

class TransactionReturnTrueGateway implements TransactionGateway
{
    public function process(ProcessTransactionInput $input): TransactionReturnTrueOutput
    {
        $urlSuccessTrue = "https://run.mocky.io/v3/2558afe3-d123-4b5a-b1aa-e25b7ed341db";
        $isPaymentApproved = json_decode(file_get_contents($urlSuccessTrue));
        return new TransactionReturnTrueOutput(success: $isPaymentApproved->success);
    }
}