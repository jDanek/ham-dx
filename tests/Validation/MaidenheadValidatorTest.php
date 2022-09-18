<?php

namespace HamDx\Validation;

use PHPUnit\Framework\TestCase;

class MaidenheadValidatorTest extends TestCase
{
    /** @var MaidenheadValidator */
    private $validator;

    public function setUp(): void
    {
        parent::setUp();
        $this->validator = new MaidenheadValidator();
    }

    public function testValidate(): void
    {
        $errorPosition = null;
        $this->assertTrue($this->validator->validate('JM', $errorPosition));
        $this->assertNull($errorPosition);

        $errorPosition = null;
        $this->assertTrue($this->validator->validate('JM54', $errorPosition));
        $this->assertNull($errorPosition);

        $errorPosition = null;
        $this->assertTrue($this->validator->validate('JM54UR', $errorPosition));
        $this->assertNull($errorPosition);

        $errorPosition = null;
        $this->assertTrue($this->validator->validate('JM54UR25', $errorPosition));
        $this->assertNull($errorPosition);

        $errorPosition = null;
        $this->assertTrue($this->validator->validate('JM54UR25CA', $errorPosition));
        $this->assertNull($errorPosition);
    }

    public function testValidateNotMatch(): void
    {
        $errorPosition = null;
        $this->assertFalse($this->validator->validate('SS', $errorPosition));
        $this->assertEquals(1, $errorPosition);

        $errorPosition = null;
        $this->assertFalse($this->validator->validate('AS', $errorPosition));
        $this->assertEquals(1, $errorPosition);

        $errorPosition = null;
        $this->assertFalse($this->validator->validate('SOME', $errorPosition));
        $this->assertEquals(1, $errorPosition);

        $errorPosition = null;
        $this->assertFalse($this->validator->validate('11AA22BB', $errorPosition));
        $this->assertEquals(1, $errorPosition);
    }

    public function testValidatePartialMatch(): void
    {
        $errorPosition = null;
        $this->assertFalse($this->validator->validate('JM54YA', $errorPosition));
        $this->assertEquals(5, $errorPosition);

        $errorPosition = null;
        $this->assertFalse($this->validator->validate('JS54UR12', $errorPosition));
        $this->assertEquals(1, $errorPosition);

        $errorPosition = null;
        $this->assertFalse($this->validator->validate('JNA4UR12', $errorPosition));
        $this->assertEquals(3, $errorPosition);

        $errorPosition = null;
        $this->assertFalse($this->validator->validate('JM54URA5CA', $errorPosition));
        $this->assertEquals(7, $errorPosition);

        $errorPosition = null;
        $this->assertFalse($this->validator->validate('11AA22BB', $errorPosition));
        $this->assertEquals(1, $errorPosition);
    }

}
