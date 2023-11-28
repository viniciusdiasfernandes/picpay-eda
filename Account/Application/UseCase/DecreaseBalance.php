<?php

namespace Account\Application\UseCase;

use Account\Application\Repository\AccountRepository;
use Account\Application\UseCase\DTO\DecreaseBalanceInput;
use Account\Application\UseCase\DTO\DecreaseBalanceOutput;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class DecreaseBalance
{
    public function __construct(
        readonly AccountRepository $accountRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function execute(DecreaseBalanceInput $input): DecreaseBalanceOutput
    {
        $account = $this->accountRepository->get($input->accountId);
        if(!$account) throw new Exception("Account do not exist", Response::HTTP_UNPROCESSABLE_ENTITY);
        $account->decreaseBalance($input->amount);
        $this->accountRepository->update($account);
        return new DecreaseBalanceOutput(
            $account->id,
            $account->getBalance()
        );
    }
}