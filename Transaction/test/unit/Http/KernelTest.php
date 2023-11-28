<?php

namespace Transaction\test\unit\Http;

use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Transaction\Infra\Http\Kernel;

class KernelTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testHandleWithExistingRoute()
    {
        $requestBody = [
            "amount" => 1,
            "senderId" => 1,
            "receiverId" => 2
        ];
        $request = Request::create(
            uri: "http://host.docker.internal/transaction",
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
            "amount" => 1,
            "senderId" => 1,
            "receiverId" => 2
        ];
        $request = Request::create(
            uri: "http://host.docker.internal/transactiontest",
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
            "amount" => 1,
            "senderId" => 1,
            "receiverId" => 2
        ];
        $request = Request::create(
            uri: "http://host.docker.internal/transaction",
            method: "GET",
            parameters: $requestBody
        );
        $kernel = new Kernel();
        $this->expectException(Exception::class);
        $kernel->handle($request);
    }
}