<?php

namespace Account\Application\UseCase;

use Account\Application\Repository\AccountRepository;
use Account\Application\UseCase\DTO\SignupInput;
use Account\Application\UseCase\DTO\SignupOutput;
use Account\Domain\Account;
use Account\Domain\AccountType;
use Account\Domain\DocumentFactory;
use Account\Domain\Email;
use Account\Domain\HashPassword;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class Signup
{
    public function __construct(
        public readonly AccountRepository $accountRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function execute(SignupInput $input): SignupOutput
    {
        $type = AccountType::from($input->type->value);
        $document = DocumentFactory::generate($type, $input->document);
        $email = new Email($input->email);
        $isAccountAlreadyCreated = $this->accountRepository->getByEmailAndDocument($email, $document);
        if ($isAccountAlreadyCreated) {
            throw new  Exception("User already exists", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $account = Account::create($input->firstName, $input->lastName, $document, $email, HashPassword::create($input->password), $type, 0);
        $accountId = $this->accountRepository->save($account);
        return new SignupOutput(
            $account->firstName,
            $account->lastName,
            $account->document->getValue(),
            $account->email->getValue(),
            $type->value,
            0,
            $accountId
        );

    }
}