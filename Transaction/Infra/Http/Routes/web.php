<?php

use App\src\Infra\Controller\AccountController;
use Transaction\Infra\Controller\TransactionController;

/**
 * @codeCoverageIgnore
 */
return [
    ['POST','/signup' , [AccountController::class, 'create']],
    ['POST','/transaction' , [TransactionController::class, 'create']]
];