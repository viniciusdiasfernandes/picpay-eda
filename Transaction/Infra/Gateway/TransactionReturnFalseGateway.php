<?php

namespace Transaction\Infra\Gateway;

use Transaction\Application\UseCases\DTO\ProcessTransactionInput;
use Transaction\Application\UseCases\DTO\ProcessTransactionOutput;

class TransactionReturnFalseGateway implements TransactionGateway
{
    public function process(ProcessTransactionInput $input): ProcessTransactionOutput
    {
        $urlSuccessTrue = "https://run.mocky.io/v3/1bcaca9e-0273-45d4-9814-b0390c8317f8";
        $isPaymentApproved = json_decode(file_get_contents($urlSuccessTrue));
        return new ProcessTransactionOutput(success: $isPaymentApproved->success);
    }
}