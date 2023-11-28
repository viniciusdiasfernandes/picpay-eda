<?php

namespace Account\Tests\unit\Validator;

use Account\Infra\Controller\Validator\IsInt;
use PHPUnit\Framework\TestCase;

class IsIntTest extends TestCase
{
    public function testValidateNotSuccess()
    {
        $data = [
            "number" => "test"
        ];
        $output = IsInt::validate($data, "number");
        $this->assertIsString($output);
        $this->assertEquals("The number should be integer", $output);
    }

    public function testValidateSuccess()
    {
        $data = [
            "number" => 1
        ];
        $output = IsInt::validate($data, "number");
        $this->assertEmpty($output);
    }

    public function testValidateEmptyValue()
    {
        $data = [];
        $output = IsInt::validate($data, "number");
        $this->assertEmpty($output);
    }
}