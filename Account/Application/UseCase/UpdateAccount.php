<?php

namespace Account\Application\UseCase;

use Account\Application\Repository\AccountRepository;
use Account\Application\UseCase\DTO\UpdateAccountInput;
use Account\Application\UseCase\DTO\UpdateAccountOutput;
use Account\Domain\DocumentFactory;
use Account\Domain\Email;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class UpdateAccount
{
    public function __construct(readonly AccountRepository $accountRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(UpdateAccountInput $input): UpdateAccountOutput
    {
        $account = $this->accountRepository->get($input->accountId);
        if (!$account) throw new Exception("Account not found", Response::HTTP_UNPROCESSABLE_ENTITY);
        $account->firstName = $input->firstName;
        $account->lastName = $input->lastName;
        $account->document = DocumentFactory::generate($input->type, $input->document);
        $account->email = new Email($input->email);
        $account->type = $input->type;
        $this->accountRepository->update($account);
        return new UpdateAccountOutput(
            accountId: $account->id,
            firstName: $account->firstName,
            lastName: $account->lastName,
            document: $account->document->getValue(),
            email: $account->email->getValue(),
            type: $account->type,
            balance: $account->getBalance()
        );
    }
}