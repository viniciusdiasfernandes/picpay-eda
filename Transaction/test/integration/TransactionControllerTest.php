<?php

namespace Transaction\Tests\integration;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Transaction\Infra\Controller\TransactionController;
use Transaction\Infra\DI\Registry;

class TransactionControllerTest extends TestCase
{
    public function testRouteIsWorking()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, "http://host.docker.internal/transaction");
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type:application/json"
        ]);
        $output = curl_exec($curl);
        $this->assertTrue($output);
    }

    public function testCreateWithSuccess()
    {
        $transactionController = new TransactionController();
        $parameters = [
            "amount" => 1,
            "senderId" => 1,
            "receiverId" => 2,
        ];
        $request = Request::create(
            uri: "http://host.docker.internal/transaction",
            method: "POST",
            parameters: $parameters
        );
        $response = $transactionController->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testCreateThrowException()
    {
        $transactionController = new TransactionController();
        $parameters = [
            "amount" => 5,
            "senderId" => 9999999,
            "receiverId" => 999999999,
        ];
        $request = Request::create(
            uri: "http://host.docker.internal/transaction",
            method: "POST",
            parameters: $parameters
        );
        $response = $transactionController->create($request);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
}