<?php

namespace Account\Infra\Repository;

use Account\Application\Repository\AccountRepository;
use Account\Domain\Account;
use Account\Domain\AccountType;
use Account\Domain\Document;
use Account\Domain\DocumentFactory;
use Account\Domain\Email;
use Account\Domain\HashPassword;
use Account\Infra\Database\Connection;
use Exception;

readonly class AccountRepositoryDatabase implements AccountRepository
{
    public function __construct(public Connection $connection)
    {
    }

    public function save(Account $account): int
    {
        $this->connection->query(
            "INSERT INTO picpay.account (name, last_name, document, email, password, type, balance) 
            VALUES ('{$account->firstName}', '{$account->lastName}', '{$account->document->getValue()}', '{$account->email->getValue()}', '{$account->password->value}', '{$account->type->value}', {$account->getBalance()})");
        return $this->connection->getLastInsertedId();
    }

    /**
     * @throws Exception
     */
    public function getByEmailAndDocument(Email $email, Document $document): Account|null
    {
        $account = $this->connection->query("SELECT * FROM picpay.account WHERE email = '{$email->getValue()}' OR document = '{$document->getValue()}'")->fetch_object();
        if (!$account) {
            return null;
        }
        $type = AccountType::from($account->type);
        $document = DocumentFactory::generate($type, $account->document);
        return Account::restore($account->name, $account->last_name, $document, new Email($account->email), HashPassword::restore($account->password, ""), $type, $account->balance, $account->id);
    }

    /**
     * @throws Exception
     */
    public function get(int $accountId): Account|null
    {
        $account = $this->connection->query("SELECT * FROM picpay.account WHERE id = {$accountId}")->fetch_object();
        if (!$account) {
            return null;
        }
        $type = AccountType::from($account->type);
        $document = DocumentFactory::generate($type, $account->document);
        return Account::restore($account->name, $account->last_name, $document, new Email($account->email), HashPassword::restore($account->password, ""), $type, $account->balance, $account->id);
    }

    public function update(Account $account): void
    {
        $this->connection->query(statement: "UPDATE picpay.account SET name = '{$account->firstName}', last_name = '{$account->lastName}', document = '{$account->document->getValue()}', email = '{$account->email->getValue()}', type = '{$account->type->value}', balance = {$account->getBalance()} WHERE id = {$account->id}");
    }

}