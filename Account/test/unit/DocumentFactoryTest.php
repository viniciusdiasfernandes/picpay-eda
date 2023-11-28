<?php
namespace Account\Tests\unit;

use Account\Domain\AccountType;
use Account\Domain\Cnpj;
use Account\Domain\Cpf;
use Account\Domain\DocumentFactory;
use Account\Tests\integration\GenerateCnpj;
use Account\Tests\integration\GenerateCpf;
use Exception;
use PHPUnit\Framework\TestCase;

class DocumentFactoryTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGenerateCpf()
    {
        $cpf = GenerateCpf::cpfRandom();
        $document = DocumentFactory::generate(AccountType::Common, $cpf);
        $this->assertInstanceOf(Cpf::class, $document);
        $this->assertEquals($cpf, $document->getValue());
    }

    /**
     * @throws Exception
     */
    public function testGenerateCnpj()
    {
        $cnpj = GenerateCnpj::cnpjRandom();
        $document = DocumentFactory::generate(AccountType::Merchant, $cnpj);
        $this->assertInstanceOf(Cnpj::class, $document);
        $this->assertEquals($cnpj, $document->getValue());
    }
}