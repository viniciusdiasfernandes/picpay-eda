<?php

namespace Account\Tests\unit\Validator;

use Account\Infra\Controller\Validator\IsRequired;
use PHPUnit\Framework\TestCase;

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