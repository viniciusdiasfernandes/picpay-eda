<?php

namespace Transaction\Infra\Controller;

use Symfony\Component\HttpFoundation\Request;
use Transaction\Infra\Controller\Validator\Validator;

class CreateTransactionValidator extends Validator
{
    private static array $rules = [
        'amount' => 'required|int|min:1',
        'senderId' => 'required|int|min:1',
        'receiverId' => 'required|int|min:1'
    ];

    public static function validate(Request $request): void
    {
        parent::execute($request->getPayload()->all(), self::$rules);
    }
}