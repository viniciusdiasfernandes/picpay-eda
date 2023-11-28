<?php

namespace Account\Tests\integration;
use Account\Application\UseCase\DTO\SignupInput;
use Account\Application\UseCase\GetAccount;
use Account\Application\UseCase\Signup;
use Account\Domain\AccountType;
use Account\Infra\Database\MySqlPromiseAdapter;
use Account\Infra\Repository\AccountRepositoryDatabase;
use Exception;
use PHPUnit\Framework\TestCase;

class GetAccountTest extends TestCase
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
        $getAccount = new GetAccount($accountRepository);
        $account = $getAccount->execute($output->accountId);
        $this->assertNotNull($account);
    }

    /**
     * @throws Exception
     */
    public function testExecuteWithNonExistentAccount()
    {
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $getAccount = new GetAccount($accountRepository);
        $this->expectException(Exception::class);
        $getAccount->execute(999999999999);
    }

    /**
     * @throws Exception
     */
    public function testExecuteWhenAccountIsNotAllowedToTransfer()
    {
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $signup = new Signup($accountRepository);
        $randomEmail = "vinidiax" . rand(10000, 99999) . "@gmail.com";
        $randomCnpj = GenerateCnpj::cnpjRandom();
        $type = AccountType::Merchant;
        $input = new SignupInput(
            "Vinicius",
            "Fernandes",
            $randomCnpj,
            $randomEmail,
            "password",
            $type,
        );
        $output = $signup->execute($input);
        $getAccount = new GetAccount($accountRepository);
        $this->expectException(Exception::class);
        $getAccount->execute($output->accountId);
    }
}