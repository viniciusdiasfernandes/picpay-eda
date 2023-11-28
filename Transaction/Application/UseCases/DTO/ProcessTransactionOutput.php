<?php

namespace Transaction\Application\UseCases\DTO;

class ProcessTransactionOutput
{
    public function __construct(
        readonly bool $success,
        readonly int $transactionId,
        readonly float $amount,
        readonly int $senderId,
        readonly int $receiverId
    )
    {

    }
}