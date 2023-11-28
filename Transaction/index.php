<?php

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Transaction\Infra\Http\Kernel;

require_once 'vendor/autoload.php';

$request = Request::createFromGlobals();
$kernel = new Kernel();
try {
    $response = $kernel->handle($request);
} catch (Exception $e) {
    $response = new JsonResponse($e->getMessage(),$e->getCode());
}
$response->send();


