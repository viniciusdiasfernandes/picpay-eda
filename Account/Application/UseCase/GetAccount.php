<?php

namespace Account\Application\UseCase;

use Account\Application\Repository\AccountRepository;
use Account\Application\UseCase\DTO\GetAccountOutput;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class GetAccount
{
    public function __construct(readonly AccountRepository $accountRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(int $accountId): GetAccountOutput
    {
        $account = $this->accountRepository->get($accountId);
        if (!$account) throw new Exception("Account not found", Response::HTTP_UNPROCESSABLE_ENTITY);
        if (!$account->isUserAllowedToTransfer()) throw new Exception("Just common users can do transfers.", Response::HTTP_UNPROCESSABLE_ENTITY);
        return new GetAccountOutput(
            accountId: $account->id,
            name: $account->firstName,
            lastName: $account->lastName,
            document: $account->document->getValue(),
            email: $account->email->getValue(),
            type: $account->type->value,
            balance: $account->getBalance()
        );
    }
}