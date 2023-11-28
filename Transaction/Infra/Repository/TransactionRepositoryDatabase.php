<?php

namespace Transaction\Infra\Repository;

use Transaction\Infra\Database\Connection;
use Transaction\Application\Repository\TransactionRepository;
use Transaction\Domain\Transaction;

readonly class TransactionRepositoryDatabase implements TransactionRepository
{
    public function __construct(public Connection $connection)
    {
    }

    public function save(Transaction $transaction): int
    {
        $status = $transaction->status->value;
        $this->connection->query("INSERT INTO picpay.transaction (amount, sender_id, receiver_id, timestamp, status) VALUES ($transaction->amount, $transaction->senderId, $transaction->receiverId, $transaction->timestamp, '{$status}')");
        return $this->connection->getLastInsertedId();
    }

    public function update(Transaction $transaction): void
    {
        $status = $transaction->status->value;
        $this->connection->query("UPDATE picpay.transaction SET amount = {$transaction->amount}, sender_id = {$transaction->senderId}, receiver_id = {$transaction->receiverId}, status = '{$status}' WHERE id = {$transaction->id}");
    }

    public function get(int $transactionId): Transaction|null
    {
        $transaction =  $this->connection->query("SELECT * FROM picpay.transaction WHERE id = {$transactionId}")->fetch_object();
        if(!$transaction) {
            return null;
        }
        return Transaction::restore(
            $transaction->amount,
            $transaction->sender_id,
            $transaction->receiver_id,
            $transaction->timestamp,
            $transaction->status,
            $transaction->id
        );

    }
}