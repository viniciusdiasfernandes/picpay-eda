<?php

namespace Transaction\Application\UseCases;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Transaction\Application\Gateway\AccountGateway;
use Transaction\Application\Repository\TransactionRepository;
use Transaction\Application\UseCases\DTO\TransactionInput;
use Transaction\Application\UseCases\DTO\TransactionOutput;
use Transaction\Application\UseCases\DTO\DecreaseBalanceInput;
use Transaction\Domain\Transaction;
use Transaction\Infra\Gateway\MailerGateway;
use Transaction\Infra\Gateway\TransactionGateway;
use Transaction\Infra\queue\Queue;

class CreateTransaction
{
    public function __construct(
        readonly TransactionRepository $transactionRepository,
        readonly TransactionGateway    $transactionGateway,
        readonly MailerGateway         $mailerGateway,
        readonly AccountGateway        $accountGateway,
        readonly Queue                 $queue
    )
    {
    }

    /**
     * @throws Exception
     */
    public function execute(TransactionInput $input): TransactionOutput
    {
        $sender = json_decode($this->accountGateway->getById($input->senderId));
        if (isset($sender->error)) throw new Exception($sender->error, Response::HTTP_UNPROCESSABLE_ENTITY);
        if ($sender->balance < $input->amount) throw new Exception("You do not have balance.", Response::HTTP_UNPROCESSABLE_ENTITY);
        $receiver = json_decode($this->accountGateway->getById($input->receiverId));
        if (isset($receiver->error)) throw new Exception($receiver->error, Response::HTTP_UNPROCESSABLE_ENTITY);
        if ($sender->accountId === $receiver->accountId) throw new Exception("You can not send money to yourself", Response::HTTP_UNPROCESSABLE_ENTITY);
        $transaction = Transaction::create($input->amount, $sender->accountId, $receiver->accountId);
        $transactionId = $this->transactionRepository->save($transaction);
        $response = $this->accountGateway->decreaseBalance($sender->accountId, $transaction->amount);
        $this->queue->publish('transactionCreated', [
            "transactionId" => $transactionId,
            "amount" => $transaction->amount,
            "senderId" => $transaction->senderId,
            "receiverId" => $transaction->receiverId
        ]);
        return new TransactionOutput($transactionId, amount: $input->amount, senderId: $input->senderId, receiverId: $input->receiverId, timestamp: time());


    }
}