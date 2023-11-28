<?php

namespace Transaction\test\unit\Validator;

use PHPUnit\Framework\TestCase;
use Transaction\Infra\Controller\Validator\IsSecure;

class IsSecureTest extends TestCase
{
    public function testValidateNotSuccess()
    {
        $data = [
            "password" => "test"
        ];
        $output = IsSecure::validate($data, "password");
        $this->assertIsString($output);
        $this->assertEquals("The password must have between 8 and 64 characters and contain at least one number, one upper case letter, one lower case letter and one special character", $output);
    }

    public function testValidateSuccess()
    {
        $data = [
            "password" => "Testing123@"
        ];
        $output = IsSecure::validate($data, "password");
        $this->assertEmpty($output);
    }

    public function testValidateEmptyValue()
    {
        $data = [];
        $output = IsSecure::validate($data, "password");
        $this->assertEmpty($output);
    }
}