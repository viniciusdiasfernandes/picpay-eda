<?php

namespace Transaction\Infra\Controller;

use Transaction\Application\UseCases\DTO\MailerSendInput;
use Transaction\Application\UseCases\DTO\ProcessTransactionInput;
use Transaction\Application\UseCases\ProcessTransaction;
use Transaction\Infra\Database\MySqlPromiseAdapter;
use Transaction\Infra\Gateway\AccountGatewayHttp;
use Transaction\Infra\Gateway\EmailSystemGateway;
use Transaction\Infra\Gateway\TransactionReturnTrueGateway;
use Transaction\Infra\Http\CurlAdapter;
use Transaction\Infra\queue\Queue;
use Transaction\Infra\queue\RabbitMQAdapter;
use Transaction\Infra\Repository\TransactionRepositoryDatabase;

class QueueController
{
    public function __construct(
        readonly Queue $queue,
    )
    {
    }

    public function consumeTransaction()
    {
        $this->queue->consume("transactionCreated", function ($message) {
            echo "[x] Message received from transactionCreated " . PHP_EOL;
            $processTransaction = new ProcessTransaction(
                new RabbitMQAdapter(),
                new TransactionReturnTrueGateway(),
                new TransactionRepositoryDatabase(new MySqlPromiseAdapter()),
                new AccountGatewayHttp(new CurlAdapter()),
                new EmailSystemGateway()
            );
            $transactionData = json_decode($message->body);
            $input = new ProcessTransactionInput(
                $transactionData->transactionId,
                $transactionData->amount,
                $transactionData->senderId,
                $transactionData->receiverId
            );
            $output = $processTransaction->execute($input);
            var_dump($output);
            $message->ack();
        });
    }


    public function consumeEmail(): void
    {
        $this->queue->consume("sendEmail", function ($message) {
            echo "[x] Message received from sendEmail " . PHP_EOL;
            $emailSystemGateway = new EmailSystemGateway();
            $emailParams = json_decode($message->body);
            $response = $emailSystemGateway->send(new MailerSendInput(
                email: $emailParams->email,
                subject: $emailParams->subject,
                message: $emailParams->message
            ));
            if($response->success) {
                echo "email sent!!!! " . PHP_EOL;
            }
            $message->ack();
        });
    }

}