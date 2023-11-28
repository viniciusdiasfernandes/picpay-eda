<?php

namespace Account\Infra\Controller;

use Account\Infra\Controller\Validator\Validator;
use Symfony\Component\HttpFoundation\Request;

class IncreaseBalanceValidator extends Validator
{
    private static array $rules = [
        'amount' => 'required'
    ];

    public static function validate(Request $request): void
    {
        parent::execute($request->getPayload()->all(), self::$rules);
    }
}