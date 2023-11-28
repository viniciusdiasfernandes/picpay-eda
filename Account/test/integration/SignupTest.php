<?php

namespace Account\Tests\integration;

use Account\Application\UseCase\DTO\SignupInput;
use Account\Application\UseCase\Signup;
use Account\Domain\AccountType;
use Account\Infra\Database\MySqlPromiseAdapter;
use Account\Infra\Repository\AccountRepositoryDatabase;
use Exception;
use PHPUnit\Framework\TestCase;

class SignupTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCreateCommonAccountWithSuccess()
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
        $account = $accountRepository->get($output->accountId);
        $this->assertEquals($input->firstName, $account->firstName);
        $this->assertEquals($input->document, $account->document->getValue());
        $this->assertEquals($input->email, $account->email->getValue());
        $connection->close();
    }

    /**
     * @throws Exception
     */
    public function testCreateMerchantAccountWithSuccess()
    {
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $signup = new Signup($accountRepository);
        $randomEmail = "vinidiax" . rand(1000, 9999) . "@gmail.com";
        $randomCpf = GenerateCnpj::cnpjRandom();
        $type = AccountType::Merchant;
        $input = new SignupInput(
            "Vinicius",
            "Fernandes",
            $randomCpf,
            $randomEmail,
            "password",
            $type,
        );
        $output = $signup->execute($input);
        $account = $accountRepository->get($output->accountId);
        $this->assertEquals($input->firstName, $account->firstName);
        $this->assertEquals($input->document, $account->document->getValue());
        $this->assertEquals($input->email, $account->email->getValue());
        $connection->close();
    }

    /**
     * @throws Exception
     */
    public function testCreateCommonAccountExistentEmail()
    {
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $signup = new Signup($accountRepository);
        $randomCpf = GenerateCpf::cpfRandom();
        $type = AccountType::Common;
        $input = new SignupInput(
            "Vinicius",
            "Fernandes",
            $randomCpf,
            "vinidiax" . rand(1000, 9999) . "@gmail.com",
            "password",
            $type,
        );
        $this->expectException(Exception::class);
        $signup->execute($input);
        $signup->execute($input);
        $connection->close();
    }

    /**
     * @throws Exception
     */
    public function testCreateCommonAccountExistentCpf()
    {
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $signup = new Signup($accountRepository);
        $type = AccountType::Common;
        $input = new SignupInput(
            "Vinicius",
            "Fernandes",
            "565.486.780-60",
            "vinidiax" . rand(1000, 9999) . "@gmail.com",
            "password",
            $type,
        );
        $this->expectException(Exception::class);
        $signup->execute($input);
        $signup->execute($input);
        $connection->close();
    }

    /**
     * @throws Exception
     */
    public function testCreateMerchantAccountExistentEmail()
    {
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $signup = new Signup($accountRepository);
        $randomCnpj = GenerateCnpj::cnpjRandom();
        $type = AccountType::Merchant;
        $input = new SignupInput(
            "Vinicius",
            "Fernandes",
            $randomCnpj,
            "vinidiax" . rand(1000, 9999) . "@gmail.com",
            "password",
            $type,
        );
        $this->expectException(Exception::class);
        $signup->execute($input);
        $signup->execute($input);
        $connection->close();
    }

    /**
     * @throws Exception
     */
    public function testCreateMerchantAccountExistentCnpj()
    {
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $signup = new Signup($accountRepository);
        $type = AccountType::Merchant;
        $input = new SignupInput(
            "Vinicius",
            "Fernandes",
            "55.023.222/0001-11",
            "vinidiax" . rand(1000, 9999) . "@gmail.com",
            "password",
            $type,
        );
        $this->expectException(Exception::class);
        $signup->execute($input);
        $signup->execute($input);
        $connection->close();
    }


}