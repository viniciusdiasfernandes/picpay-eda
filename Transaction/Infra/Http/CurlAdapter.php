<?php

namespace Transaction\Infra\Http;

use Exception;

class CurlAdapter implements HttpClient
{

    /**
     * @throws Exception
     */
    public function get(string $url): bool|string
    {
        try {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (Exception $e) {
            throw new Exception($e);
        }

    }

    /**
     * @throws Exception
     */
    public function post(string $url, array $data): bool|string
    {
        try {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     * @throws Exception
     */
    public function put(string $url, array $data): bool|string
    {
        try {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_PUT, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}