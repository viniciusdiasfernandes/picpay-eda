<?php

namespace Account\Tests\unit;


use Account\Domain\Account;
use Account\Domain\AccountType;
use Account\Domain\Cnpj;
use Account\Domain\Cpf;
use Account\Domain\Email;
use Account\Domain\HashPassword;
use Account\Tests\integration\GenerateCnpj;
use Account\Tests\integration\GenerateCpf;
use Exception;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAccountWithPermissionToTransfer()
    {
        $account = Account::create(
            firstName: "Test",
            lastName: "Test",
            document: new Cpf(GenerateCpf::cpfRandom()),
            email: new Email("vinidiax@gmail.com"),
            password: HashPassword::create("test"),
            type: AccountType::Common,
            balance: 0
        );
        $this->assertTrue($account->isUserAllowedToTransfer());
    }

    /**
     * @throws Exception
     */
    public function testAccountWithoutPermissionToTransfer()
    {
        $account = Account::create(
            firstName: "Test",
            lastName: "Test",
            document: new Cnpj(GenerateCnpj::cnpjRandom()),
            email: new Email("vinidiax@gmail.com"),
            password: HashPassword::create("test"),
            type: AccountType::Merchant,
            balance: 0
        );
        $this->assertFalse($account->isUserAllowedToTransfer());
    }

    /**
     * @throws Exception
     */
    public function testIsBalanceIsNotGreaterThenAmountToTransfer()
    {
        $account = Account::create(
            firstName: "Test",
            lastName: "Test",
            document: new Cnpj(GenerateCnpj::cnpjRandom()),
            email: new Email("vinidiax@gmail.com"),
            password: HashPassword::create("test"),
            type: AccountType::Merchant,
            balance: 0
        );
        $this->assertFalse($account->isBalanceGreaterThenAmountToTransfer(500));
    }

    /**
     * @throws Exception
     */
    public function testIsBalanceGreaterThenAmountToTransfer()
    {
        $account = Account::create(
            firstName: "Test",
            lastName: "Test",
            document: new Cnpj(GenerateCnpj::cnpjRandom()),
            email: new Email("vinidiax@gmail.com"),
            password: HashPassword::create("test"),
            type: AccountType::Merchant,
            balance: 5000
        );
        $this->assertTrue($account->isBalanceGreaterThenAmountToTransfer(500));
    }

    /**
     * @throws Exception
     */
    public function testIncreaseBalance()
    {
        $account = Account::create(
            firstName: "Test",
            lastName: "Test",
            document: new Cpf(GenerateCpf::cpfRandom()),
            email: new Email("vinidiax@gmail.com"),
            password: HashPassword::create("test"),
            type: AccountType::Common,
            balance: 0
        );
        $previousBalance = $account->getBalance();
        $account->increaseBalance(10);
        $this->assertEquals($previousBalance + 10, $account->getBalance());
    }

    /**
     * @throws Exception
     */
    public function testDecreaseBalance()
    {
        $account = Account::create(
            firstName: "Test",
            lastName: "Test",
            document: new Cpf(GenerateCpf::cpfRandom()),
            email: new Email("vinidiax@gmail.com"),
            password: HashPassword::create("test"),
            type: AccountType::Common,
            balance: 50
        );
        $previousBalance = $account->getBalance();
        $account->decreaseBalance(10);
        $this->assertEquals($previousBalance - 10, $account->getBalance());
    }

    /**
     * @throws Exception
     */
    public function testDecreaseBalanceWhenBalanceIsNotEnough()
    {
        $account = Account::create(
            firstName: "Test",
            lastName: "Test",
            document: new Cpf(GenerateCpf::cpfRandom()),
            email: new Email("vinidiax@gmail.com"),
            password: HashPassword::create("test"),
            type: AccountType::Common,
            balance: 0
        );
        $this->expectException(Exception::class);
        $account->decreaseBalance(10);
    }
}