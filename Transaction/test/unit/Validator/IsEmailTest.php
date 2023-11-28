<?php

namespace Transaction\test\unit\Validator;

use PHPUnit\Framework\TestCase;
use Transaction\Infra\Controller\Validator\IsEmail;

class IsEmailTest extends TestCase
{
    public function testValidateNotSuccess()
    {
        $data = [
            "email" => "test"
        ];
        $isValidEmail = IsEmail::validate($data, "email");
        $this->assertIsString($isValidEmail);
        $this->assertEquals("test is not a valid email address", $isValidEmail);
    }

    public function testValidateSuccess()
    {
        $data = [
            "email" => "vinidiax@gmail.com"
        ];
        $isValidEmail = IsEmail::validate($data, "email");
        $this->assertEmpty($isValidEmail);
    }

    public function testValidateEmptyValue()
    {
        $data = [];
        $isValidEmail = IsEmail::validate($data, "email");
        $this->assertEmpty($isValidEmail);
    }
}