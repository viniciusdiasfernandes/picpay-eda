<?php

namespace Account\Tests\integration;

use Account\Application\UseCase\DTO\IncreaseBalanceInput;
use Account\Application\UseCase\DTO\SignupInput;
use Account\Application\UseCase\GetAccount;
use Account\Application\UseCase\IncreaseBalance;
use Account\Application\UseCase\Signup;
use Account\Domain\AccountType;
use Account\Infra\Database\MySqlPromiseAdapter;
use Account\Infra\Repository\AccountRepositoryDatabase;
use Exception;
use PHPUnit\Framework\TestCase;

class IncreaseBalanceTest extends TestCase
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
        $output = $signup->execute($input);
        $increaseBalance = new IncreaseBalance($accountRepository);
        $input = new IncreaseBalanceInput($output->accountId, 500);
        $increaseAccountOutput = $increaseBalance->execute($input);
        $account = $accountRepository->get($output->accountId);
        $this->assertEquals($account->id, $increaseAccountOutput->accountId);
        $this->assertEquals(500,$account->getBalance());
    }

    /**
     * @throws Exception
     */
    public function testExecuteWithNonExistentAccount()
    {
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $increaseBalance = new IncreaseBalance($accountRepository);
        $input = new IncreaseBalanceInput(99999999, 500);
        $this->expectException(Exception::class);
        $increaseBalance->execute($input);
    }
}