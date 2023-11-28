<?php

namespace Transaction\Application\UseCases\DTO;

readonly class ProcessTransactionInput
{
    public function __construct(
        public int    $transactionId,
        public float  $amount,
        public int    $senderId,
        public int    $receiverId
    )
    {
    }
}