<?php

namespace Transaction\Application\UseCases\DTO;

readonly class TransactionInput
{
    public function __construct(
        public float $amount,
        public int   $senderId,
        public int   $receiverId
    )
    {
    }
}