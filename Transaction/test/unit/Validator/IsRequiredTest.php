<?php

namespace Transaction\test\unit\Validator;

use PHPUnit\Framework\TestCase;
use Transaction\Infra\Controller\Validator\IsRequired;

class IsRequiredTest extends TestCase
{
    public function testValidateNotSuccess()
    {
        $data = [
            "name" => ""
        ];
        $output = IsRequired::validate($data, "name");
        $this->assertIsString($output);
        $this->assertEquals("The name is required", $output);
    }

    public function testValidateSuccess()
    {
        $data = [
            "name" => "test"
        ];
        $output = IsRequired::validate($data, "name");
        $this->assertEmpty($output);
    }
}