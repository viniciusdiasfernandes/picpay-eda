<?php

namespace Transaction\Infra\Http;

interface HttpClient
{
    public function get(string $url);
    public function post(string $url, array $data): bool|string;
    public function put(string $url, array $data);
}