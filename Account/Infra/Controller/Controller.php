<?php

namespace Account\Infra\Controller;

use Symfony\Component\HttpFoundation\Request;

abstract class Controller
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}