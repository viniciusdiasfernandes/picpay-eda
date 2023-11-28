<?php

namespace Transaction\test\unit\Validator;

use PHPUnit\Framework\TestCase;
use Transaction\Infra\Controller\Validator\IsInt;

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