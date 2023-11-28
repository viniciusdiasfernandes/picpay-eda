<?php

namespace Transaction\test\unit\Validator;

use PHPUnit\Framework\TestCase;
use Transaction\Infra\Controller\Validator\IsBetween;

class IsBetweenTest extends TestCase
{
    public function testValidateNotSuccess()
    {
        $data = [
            "name" => "test"
        ];
        $isBetweenText = IsBetween::validate($data, "name", [1,2]);
        $this->assertIsString($isBetweenText);
        $this->assertEquals("The name must have between 1 and 2 characters", $isBetweenText);
    }

    public function testValidateSuccess()
    {
        $data = [
            "name" => "test"
        ];
        $isBetweenText = IsBetween::validate($data, "name", [1,20]);
        $this->assertEmpty($isBetweenText);
    }

    public function testValidateEmptyValue()
    {
        $data = [];
        $isBetweenText = IsBetween::validate($data, "name", [1,20]);
        $this->assertEmpty($isBetweenText);
    }
}