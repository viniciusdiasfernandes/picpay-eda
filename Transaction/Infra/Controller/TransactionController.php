<?php

namespace Transaction\Infra\Controller;

use Transaction\Infra\Database\MySqlPromiseAdapter;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Transaction\Application\UseCases\CreateTransaction;
use Transaction\Application\UseCases\DTO\TransactionInput;
use Transaction\Infra\Gateway\AccountGatewayHttp;
use Transaction\Infra\Gateway\EmailSystemGateway;
use Transaction\Infra\Gateway\TransactionReturnTrueGateway;
use Transaction\Infra\Http\CurlAdapter;
use Transaction\Infra\queue\RabbitMQAdapter;
use Transaction\Infra\Repository\TransactionRepositoryDatabase;

class TransactionController
{
    public function create(Request $request): JsonResponse
    {
        CreateTransactionValidator::validate($request);
        $payload = $request->getPayload();
        $input = new TransactionInput(
            amount: $payload->get('amount'),
            senderId: $payload->get('senderId'),
            receiverId: $payload->get('receiverId')
        );
        $connection = new MySqlPromiseAdapter();
        $transactionRepository = new TransactionRepositoryDatabase($connection);
        $transactionGateway = new TransactionReturnTrueGateway();
        $emailSystemGateway = new EmailSystemGateway();
        $httpClient = new CurlAdapter();
        $accountGateway = new AccountGatewayHttp($httpClient);
        $rabbitMQAdapter = new RabbitMQAdapter();
        $createTransaction = new CreateTransaction($transactionRepository, $transactionGateway, $emailSystemGateway, $accountGateway, $rabbitMQAdapter);
        try {
            $output = $createTransaction->execute($input);
        } catch (Exception $e) {
            return new JsonResponse(["Error" => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse($output, Response::HTTP_CREATED);
    }
}