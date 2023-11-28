<?php

namespace Transaction\Tests\integration;

use Exception;
use PHPUnit\Framework\TestCase;
use Transaction\Application\UseCases\CreateTransaction;
use Transaction\Application\UseCases\DTO\ProcessTransactionInput;
use Transaction\Application\UseCases\DTO\TransactionInput;
use Transaction\Application\UseCases\ProcessTransaction;
use Transaction\Infra\Database\MySqlPromiseAdapter;
use Transaction\Infra\Gateway\AccountGatewayHttp;
use Transaction\Infra\Gateway\EmailSystemGateway;
use Transaction\Infra\Gateway\TransactionReturnTrueGateway;
use Transaction\Infra\Http\CurlAdapter;
use Transaction\Infra\queue\RabbitMQAdapter;
use Transaction\Infra\Repository\TransactionRepositoryDatabase;

class ProcessTransactionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testExecuteOnSuccessCase()
    {
        $connection = new MySqlPromiseAdapter();
        $transactionRepository = new TransactionRepositoryDatabase($connection);
        $transactionGateway = new TransactionReturnTrueGateway();
        $emailSystemGateway = new EmailSystemGateway();
        $curlAdapter = new CurlAdapter();
        $accountGateway = new AccountGatewayHttp($curlAdapter);
        $queue = new RabbitMQAdapter();
        $processTransaction = new ProcessTransaction($queue, $transactionGateway, $transactionRepository, $accountGateway, $emailSystemGateway);
        $createTransaction = new CreateTransaction($transactionRepository,$transactionGateway,$emailSystemGateway,$accountGateway,$queue);
        $inputCreateTransaction = new TransactionInput(amount: 5, senderId: 1, receiverId: 2);
        $transaction = $createTransaction->execute($inputCreateTransaction);
        $processTransactionInput = new ProcessTransactionInput(
            $transaction->transactionId,
            $transaction->amount,
            $transaction->senderId,
            $transaction->receiverId
        );
        $processTransactionOutput = $processTransaction->execute($processTransactionInput);
        $this->assertEquals($inputCreateTransaction->amount, $processTransactionOutput->amount);

    }

}