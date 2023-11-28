<?php

namespace Account\Application\UseCase;

use Account\Application\Repository\AccountRepository;
use Account\Application\UseCase\DTO\IncreaseBalanceInput;
use Account\Application\UseCase\DTO\IncreaseBalanceOutput;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class IncreaseBalance
{
    public function __construct(
        readonly AccountRepository $accountRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function execute(IncreaseBalanceInput $input): IncreaseBalanceOutput
    {
        $account = $this->accountRepository->get($input->accountId);
        if(!$account) throw new Exception("Account do not exist", Response::HTTP_UNPROCESSABLE_ENTITY);
        $account->increaseBalance($input->amount);
        $this->accountRepository->update($account);
        return new IncreaseBalanceOutput(
            $account->id,
            $account->getBalance()
        );
    }
}