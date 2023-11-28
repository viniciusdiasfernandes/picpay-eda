<?php

namespace Transaction\Tests\integration;

use Exception;
use PHPUnit\Framework\TestCase;
use Transaction\Application\UseCases\CreateTransaction;
use Transaction\Application\UseCases\DTO\TransactionInput;
use Transaction\Infra\Database\MySqlPromiseAdapter;
use Transaction\Infra\Gateway\AccountGatewayHttp;
use Transaction\Infra\Gateway\EmailSystemGateway;
use Transaction\Infra\Gateway\TransactionReturnTrueGateway;
use Transaction\Infra\Http\CurlAdapter;
use Transaction\Infra\queue\RabbitMQAdapter;
use Transaction\Infra\Repository\TransactionRepositoryDatabase;

class CreateTransactionTest extends TestCase
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
        $createTransaction = new CreateTransaction($transactionRepository, $transactionGateway, $emailSystemGateway, $accountGateway, $queue);
        $amount = 100;
        $input = new TransactionInput(amount: $amount, senderId: 1, receiverId: 2);
        $output = $createTransaction->execute($input);
        $this->assertNotNull($output->transactionId);
        $connection->close();
    }

    /**
     * @throws Exception
     */
    public function testExecuteWhenSenderDoNotExists()
    {
        $connection = new MySqlPromiseAdapter();
        $transactionRepository = new TransactionRepositoryDatabase($connection);
        $transactionGateway = new TransactionReturnTrueGateway();
        $emailSystemGateway = new EmailSystemGateway();
        $curlAdapter = new CurlAdapter();
        $accountGateway = new AccountGatewayHttp($curlAdapter);
        $queue = new RabbitMQAdapter();
        $createTransaction = new CreateTransaction($transactionRepository, $transactionGateway, $emailSystemGateway, $accountGateway, $queue);
        $amount = 100;
        $input = new TransactionInput(amount: $amount, senderId: 999999, receiverId: 2);
        $this->expectException(Exception::class);
        $createTransaction->execute($input);
        $connection->close();
    }

    /**
     * @throws Exception
     */
    public function testExecuteWhenReceiverDoNotExists()
    {
        $connection = new MySqlPromiseAdapter();
        $transactionRepository = new TransactionRepositoryDatabase($connection);
        $transactionGateway = new TransactionReturnTrueGateway();
        $emailSystemGateway = new EmailSystemGateway();
        $curlAdapter = new CurlAdapter();
        $accountGateway = new AccountGatewayHttp($curlAdapter);
        $queue = new RabbitMQAdapter();
        $createTransaction = new CreateTransaction($transactionRepository, $transactionGateway, $emailSystemGateway, $accountGateway, $queue);
        $amount = 100;
        $input = new TransactionInput(amount: $amount, senderId: 1, receiverId: 99999999);
        $this->expectException(Exception::class);
        $createTransaction->execute($input);
        $connection->close();
    }

    /**
     * @throws Exception
     */
    public function testExecuteWhenDoNotHaveBalance()
    {
        $connection = new MySqlPromiseAdapter();
        $transactionRepository = new TransactionRepositoryDatabase($connection);
        $transactionGateway = new TransactionReturnTrueGateway();
        $emailSystemGateway = new EmailSystemGateway();
        $curlAdapter = new CurlAdapter();
        $accountGateway = new AccountGatewayHttp($curlAdapter);
        $queue = new RabbitMQAdapter();
        $createTransaction = new CreateTransaction($transactionRepository, $transactionGateway, $emailSystemGateway, $accountGateway, $queue);
        $amount = 9999999999999999;
        $input = new TransactionInput(amount: $amount, senderId: 1, receiverId: 99999999);
        $this->expectException(Exception::class);
        $createTransaction->execute($input);
        $connection->close();
    }

    /**
     * @throws Exception
     */
    public function testExecuteWhenTryToSendMoneyToSameAccount()
    {
        $connection = new MySqlPromiseAdapter();
        $transactionRepository = new TransactionRepositoryDatabase($connection);
        $transactionGateway = new TransactionReturnTrueGateway();
        $emailSystemGateway = new EmailSystemGateway();
        $curlAdapter = new CurlAdapter();
        $accountGateway = new AccountGatewayHttp($curlAdapter);
        $queue = new RabbitMQAdapter();
        $createTransaction = new CreateTransaction($transactionRepository, $transactionGateway, $emailSystemGateway, $accountGateway, $queue);
        $amount = 1;
        $input = new TransactionInput(amount: $amount, senderId: 1, receiverId: 1);
        $this->expectException(Exception::class);
        $createTransaction->execute($input);
        $connection->close();
    }
}