<?php

namespace Transaction\Infra\Gateway;

use Transaction\Application\Gateway\AccountGateway;
use Transaction\Infra\Http\HttpClient;

class AccountGatewayHttp implements AccountGateway
{
    public function __construct(readonly HttpClient $httpClient)
    {
    }

    public function getById(int $accountId)
    {
        return $this->httpClient->get("http://host.docker.internal:81/account/{$accountId}");
    }
    public function increaseBalance(int $accountId, float $amount): bool|string
    {
        $data = [
            "amount" => $amount
        ];
        return $this->httpClient->post("http://host.docker.internal:81/account/{$accountId}/balance/increase", $data);
    }

    public function decreaseBalance(int $accountId, float $amount): bool|string
    {
        $data = [
            "amount" => $amount
        ];
        return $this->httpClient->post("http://host.docker.internal:81/account/{$accountId}/balance/decrease", $data);
    }
}