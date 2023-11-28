<?php

namespace Transaction\Application\UseCases;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Transaction\Application\Gateway\AccountGateway;
use Transaction\Application\Repository\TransactionRepository;
use Transaction\Application\UseCases\DTO\MailerSendInput;
use Transaction\Application\UseCases\DTO\ProcessTransactionInput;
use Transaction\Application\UseCases\DTO\ProcessTransactionOutput;
use Transaction\Infra\Gateway\MailerGateway;
use Transaction\Infra\Gateway\TransactionGateway;
use Transaction\Infra\queue\Queue;

class ProcessTransaction
{
    public function __construct(
        readonly Queue                 $queue,
        readonly TransactionGateway    $transactionGateway,
        readonly TransactionRepository $transactionRepository,
        readonly AccountGateway        $accountGateway,
        readonly MailerGateway         $mailerGateway
    )
    {
    }

    /**
     * @throws Exception
     */
    public
    function execute(ProcessTransactionInput $input): ProcessTransactionOutput
    {
        $transaction = $this->transactionRepository->get($input->transactionId);
        if (!$transaction) throw new Exception("Transaction not found", Response::HTTP_UNPROCESSABLE_ENTITY);
        $response = $this->transactionGateway->process($input);
        if ($response->success) {
            $transaction->finish();
            $this->accountGateway->increaseBalance($transaction->receiverId, $transaction->amount);
        } else {
            $transaction->cancel();
            $this->accountGateway->increaseBalance($transaction->senderId, $transaction->amount);
        }
        $this->transactionRepository->update($transaction);
        $emailParams = ["email" => "vinidiax@gmail.com", "subject" => "Money received", "message" => "You are rich!"];
        $this->queue->publish("sendEmail", $emailParams);
        return new ProcessTransactionOutput(
            $response->success,
            $transaction->id,
            $transaction->amount,
            $transaction->senderId,
            $transaction->receiverId
        );
    }
}