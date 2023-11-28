<?php

namespace Transaction\test\unit\Validator;

use PHPUnit\Framework\TestCase;
use Transaction\Infra\Controller\Validator\IsMax;

class IsMaxTest extends TestCase
{
    public function testValidateNotSuccess()
    {
        $data = [
            "name" => "test"
        ];
        $output = IsMax::validate($data, "name", 2);
        $this->assertIsString($output);
        $this->assertEquals("The name must have at most 2 characters", $output);
    }

    public function testValidateSuccess()
    {
        $data = [
            "name" => "test"
        ];
        $output = IsMax::validate($data, "name", 20);
        $this->assertEmpty($output);
    }

    public function testValidateEmptyValue()
    {
        $data = [];
        $output = IsMax::validate($data, "name", 20);
        $this->assertEmpty($output);
    }
}