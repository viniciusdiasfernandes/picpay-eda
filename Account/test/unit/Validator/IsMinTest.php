<?php

namespace Account\Tests\unit\Validator;

use Account\Infra\Controller\Validator\IsMin;
use PHPUnit\Framework\TestCase;

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