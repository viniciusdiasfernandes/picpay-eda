<?php

namespace Transaction\test\unit\Validator;

use PHPUnit\Framework\TestCase;
use Transaction\Infra\Controller\Validator\IsMin;

class IsMinTest extends TestCase
{
    public function testValidateNotSuccess()
    {
        $data = [
            "name" => "test"
        ];
        $output = IsMin::validate($data, "name", [10]);
        $this->assertIsString($output);
        $this->assertEquals("The name must have at least 10 characters", $output);
    }

    public function testValidateSuccess()
    {
        $data = [
            "name" => "test"
        ];
        $output = IsMin::validate($data, "name", [2]);
        $this->assertEmpty($output);
    }

    public function testEmptyValue()
    {
        $data = [];
        $output = IsMin::validate($data, "name", [2]);
        $this->assertEmpty($output);
    }
}