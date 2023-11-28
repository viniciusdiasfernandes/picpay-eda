<?php

namespace Transaction\Application\Repository;

use Transaction\Domain\Transaction;

interface TransactionRepository
{
    public function save(Transaction $transaction): int;

    public function update(Transaction $transaction): void;

    public function get(int $transactionId): Transaction|null;
}