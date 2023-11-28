<?php

use Account\Application\UseCase\DTO\SignupInput;
use Account\Application\UseCase\DTO\UpdateAccountInput;
use Account\Application\UseCase\Signup;
use Account\Application\UseCase\UpdateAccount;
use Account\Domain\AccountType;
use Account\Infra\Database\MySqlPromiseAdapter;
use Account\Infra\Repository\AccountRepositoryDatabase;
use Account\Tests\integration\GenerateCpf;
use PHPUnit\Framework\TestCase;

class UpdateAccountTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testExecuteWithSuccess()
    {
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $signup = new Signup($accountRepository);
        $randomEmail = "vinidiax" . rand(1000, 9999) . "@gmail.com";
        $randomCpf = GenerateCpf::cpfRandom();
        $type = AccountType::Common;
        $input = new SignupInput(
            "Vinicius",
            "Fernandes",
            $randomCpf,
            $randomEmail,
            "password",
            $type,
        );
        $account = $signup->execute($input);
        $updateAccount = new UpdateAccount($accountRepository);
        $updateAccountInput = new UpdateAccountInput(
            $account->accountId,
            "New First Name",
            "New Last Name",
            $account->document,
            $account->email,
            AccountType::from($account->type)
        );
        $account = $updateAccount->execute($updateAccountInput);
        $updatedAccount = $accountRepository->get($account->accountId);
        $this->assertEquals($updatedAccount->firstName, $updateAccountInput->firstName);
    }

    /**
     * @throws Exception
     */
    public function testExecuteWithNonExistentAccount()
    {
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $updateAccount = new UpdateAccount($accountRepository);
        $updateAccountInput = new UpdateAccountInput(
            99999999999,
            "New First Name",
            "New Last Name",
            GenerateCpf::cpfRandom(),
            "vinidiax@gmail.com",
            AccountType::Common
        );
        $this->expectException(Exception::class);
        $updateAccount->execute($updateAccountInput);
    }
}