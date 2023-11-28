<?php

namespace Account\Tests\unit;

use Account\Infra\Database\MySqlPromiseAdapter;
use Account\Infra\Http\Kernel;
use Account\Infra\Repository\AccountRepositoryDatabase;
use Account\Tests\integration\GenerateCpf;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class KernelTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testHandleWithExistingRoute()
    {
        $requestBody = [
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
            parameters: $requestBody
        );
        $kernel = new Kernel();
        $response = $kernel->handle($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testHandleWithExistingRouteAndParamsOnUrl()
    {
        $requestBody = [
            "name" => "Vinicius",
            "lastName" => "Fernandes",
            "document" => GenerateCpf::cpfRandom(),
            "email" => "vini" . rand(1000, 9999) . "@gmail.com",
            "password" => "Teste@1345667",
            "type" => "common"
        ];
        $request = Request::create(
            uri: "http://host.docker.internal:81/signup?test=1",
            method: "POST",
            parameters: $requestBody
        );
        $kernel = new Kernel();
        $response = $kernel->handle($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testHandleWithNonExistingRoute()
    {
        $requestBody = [
            "name" => "Vinicius",
            "lastName" => "Fernandes",
            "document" => GenerateCpf::cpfRandom(),
            "email" => "vini" . rand(1000, 9999) . "@gmail.com",
            "password" => "Teste@1345667",
            "type" => "common"
        ];
        $request = Request::create(
            uri: "http://host.docker.internal:81/signuptest",
            method: "POST",
            parameters: $requestBody
        );
        $kernel = new Kernel();
        $this->expectException(Exception::class);
        $kernel->handle($request);
    }

    public function testHandleWithMethodNotAllowed()
    {
        $requestBody = [
            "name" => "Vinicius",
            "lastName" => "Fernandes",
            "document" => GenerateCpf::cpfRandom(),
            "email" => "vini" . rand(1000, 9999) . "@gmail.com",
            "password" => "Teste@1345667",
            "type" => "common"
        ];
        $request = Request::create(
            uri: "http://host.docker.internal:81/signup",
            method: "DELETE",
            parameters: $requestBody
        );
        $kernel = new Kernel();
        $this->expectException(Exception::class);
        $kernel->handle($request);
    }
}