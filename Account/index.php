<?php

use Account\Infra\Http\Kernel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

require_once 'vendor/autoload.php';

$request = Request::createFromGlobals();
$requestBody = (array)json_decode(file_get_contents("php://input"));
$kernel = new Kernel();
try {
    $response = $kernel->handle($request);
} catch (Exception $e) {
    $response = new JsonResponse($e->getMessage(),$e->getCode());
}
$response->send();


