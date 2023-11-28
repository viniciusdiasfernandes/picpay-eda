<?php

namespace Account\Tests\integration;

use Account\Infra\Controller\AccountController;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountControllerTest extends TestCase
{
    public function testRoutesAreWorking()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, "http://host.docker.internal:81/signup");
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type:application/json"
        ]);
        $output = curl_exec($curl);
        curl_close($curl);
        $this->assertTrue($output);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, "http://host.docker.internal:81/account/1");
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type:application/json"
        ]);
        $output = curl_exec($curl);
        $this->assertTrue($output);
    }

    /**
     * @throws Exception
     */
    public function testCreateWithSuccess()
    {
        $parameters = [
            "name" => "Vinicius",
            "lastName" => "Fernandes",
            "document" => GenerateCpf::cpfRandom(),
            "email" => "vini" . rand(1000, 9999) . "@gmail.com",
            "password" => "Teste@1345667",
            "type" => "common"
        ];
        $request = Request::create(
            uri: "http://host.docker.internal:81/signup",
            method: "POST",
            parameters: $parameters
        );
        $accountController = new AccountController($request);
        $response = $accountController->create();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testCreateThrowException()
    {
        $cpf = GenerateCpf::cpfRandom();
        $parameters = [
            "name" => "Vinicius",
            "lastName" => "Fernandes",
            "document" => $cpf,
            "email" => "vini" . rand(1000, 9999) . "@gmail.com",
            "password" => "Teste@1345667",
            "type" => "common"
        ];
        $request = Request::create(
            uri: "http://host.docker.internal:81/signup",
            method: "POST",
            parameters: $parameters
        );
        $accountController = new AccountController($request);
        $accountController->create();
        $response = $accountController->create();
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testGetAccountWithSuccess()
    {
        $parameters = [
            "name" => "Vinicius",
            "lastName" => "Fernandes",
            "document" => GenerateCpf::cpfRandom(),
            "email" => "vini" . rand(1000, 9999) . "@gmail.com",
            "password" => "Teste@1345667",
            "type" => "common"
        ];
        $requestCreate = Request::create(
            uri: "http://host.docker.internal:81/signup",
            method: "POST",
            parameters: $parameters
        );
        $accountController = new AccountController($requestCreate);
        $response = $accountController->create();
        $accountData = json_decode($response->getContent());
        $getAccountResponse = $accountController->getAccount($accountData->accountId);
        $this->assertEquals(Response::HTTP_OK, $getAccountResponse->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testGetAccountThrowException()
    {
        $parameters = [
            "name" => "Vinicius",
            "lastName" => "Fernandes",
            "document" => GenerateCpf::cpfRandom(),
            "email" => "vini" . rand(1000, 9999) . "@gmail.com",
            "password" => "Teste@1345667",
            "type" => "common"
        ];
        $requestCreate = Request::create(
            uri: "http://host.docker.internal:81/signup",
            method: "POST",
            parameters: $parameters
        );
        $accountController = new AccountController($requestCreate);
        $accountController->create();
        $getAccountResponse = $accountController->getAccount(9999999999999);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $getAccountResponse->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testIncreaseBalance()
    {
        $parameters = [
            "amount" => 50,
        ];
        $requestCreate = Request::create(
            uri: "http://host.docker.internal:81/account/1/balance/increase",
            method: "POST",
            parameters: $parameters
        );
        $accountController = new AccountController($requestCreate);
        $increaseBalanceResponse = $accountController->increaseBalance(1);
        $this->assertEquals(Response::HTTP_OK, $increaseBalanceResponse->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testIncreaseBalanceThrowException()
    {
        $parameters = [
            "amount" => 50,
        ];
        $requestCreate = Request::create(
            uri: "http://host.docker.internal:81/account/9999999999/balance/increase",
            method: "POST",
            parameters: $parameters
        );
        $accountController = new AccountController($requestCreate);
        $increaseBalanceResponse = $accountController->increaseBalance(9999999999);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $increaseBalanceResponse->getStatusCode());
    }
}