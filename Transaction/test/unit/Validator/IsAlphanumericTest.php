<?php

namespace Transaction\test\unit\Validator;

use PHPUnit\Framework\TestCase;
use Transaction\Infra\Controller\Validator\IsAlphanumeric;

class IsAlphanumericTest extends TestCase
{
    public function testValidateNotSuccess()
    {
        $data = [
            "name" => "test"
        ];
        $isAlphanumericText = IsAlphanumeric::validate($data, "name");
        $this->assertIsString($isAlphanumericText);
        $this->assertEquals("The name should have only letters and numbers", $isAlphanumericText);
    }

    public function testValidateSuccess()
    {
        $data = [
            "name" => 1
        ];
        $isAlphanumeric = IsAlphanumeric::validate($data, "name");
        $this->assertEmpty($isAlphanumeric);
    }

    public function testValidateEmptyValue()
    {
        $data = [];
        $isAlphanumeric = IsAlphanumeric::validate($data, "name");
        $this->assertEmpty($isAlphanumeric);
    }
}