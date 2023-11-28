<?php

namespace Account\Tests\unit\Validator;

use Account\Infra\Controller\Validator\IsSame;
use PHPUnit\Framework\TestCase;

class IsSameTest extends TestCase
{
    public function testValidateNotSuccess()
    {
        $data = [
            "name" => "testing"
        ];
        $output = IsSame::validate($data, "name", ["test"]);
        $this->assertIsString($output);
        $this->assertEquals("The name must match with test", $output);
    }

    public function testValidateSuccess()
    {
        $data = [
            "name" => "test"
        ];
        $output = IsSame::validate($data, "name",["test"]);
        $this->assertEmpty($output);
    }
}