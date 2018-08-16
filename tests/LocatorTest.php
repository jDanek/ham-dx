<?php declare(strict_types=1);

namespace Toolia\HamDx;

use PHPUnit\Framework\TestCase;


class LocatorTest extends TestCase
{
    /** @var  Locator */
    protected $locator1;

    /** @var  Locator */
    protected $locator2;

    function testValidationQthLocator()
    {
        // valid
        $this->assertTrue(Locator::validation('AA'));
        $this->assertTrue(Locator::validation('DF11'));
        $this->assertTrue(Locator::validation('AA00aa'));
        $this->assertTrue(Locator::validation('ab12cd44'));
        $this->assertTrue(Locator::validation('PO00OO37eb'));
        $this->assertTrue(Locator::validation('do00ob52xx'));

        // non-valid
        $this->assertFalse(Locator::validation('12'));
        $this->assertFalse(Locator::validation('hello world'));
        $this->assertFalse(Locator::validation('e25'));
        $this->assertFalse(Locator::validation('0P45'));
        $this->assertFalse(Locator::validation('ft57'));
        $this->assertFalse(Locator::validation('B27OP'));
        $this->assertFalse(Locator::validation('AX00aa'));
        $this->assertFalse(Locator::validation('BS99ia'));
        $this->assertFalse(Locator::validation('SB36po'));
        $this->assertFalse(Locator::validation('RB36za'));
        $this->assertFalse(Locator::validation('AA00aa0'));
        $this->assertFalse(Locator::validation('SA00aa00'));
        $this->assertFalse(Locator::validation('AB12CD3'));
        $this->assertFalse(Locator::validation('DF11GB62Yb'));

    }

    function testCreateFromQth()
    {
        $this->assertInstanceOf('Toolia\HamDx\Locator', Locator::fromQth("JN12PH"));
    }

    function testCreateFromLocation()
    {
        $this->assertInstanceOf('Toolia\HamDx\Locator', Locator::fromCoords(42.3333333333, 3.25));
    }

}
