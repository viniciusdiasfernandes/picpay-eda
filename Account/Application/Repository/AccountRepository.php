<?php

namespace Account\Application\Repository;

use Account\Domain\Account;
use Account\Domain\Document;
use Account\Domain\Email;

interface AccountRepository
{
    public function save(Account $account): int;
    public function getByEmailAndDocument(Email $email, Document $document): Account|null;
    public function get(int $accountId): Account|null;
    public function update(Account $account): void;

}