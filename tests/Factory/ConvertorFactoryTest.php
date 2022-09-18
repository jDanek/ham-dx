<?php

namespace HamDx\Factory;

use HamDx\Converter\MaidenheadConverter;
use PHPUnit\Framework\TestCase;

class ConvertorFactoryTest extends TestCase
{

    public function testCreateMaidenhead()
    {
        $factory = new ConvertorFactory();
        $mhConvertor = $factory->createMaidenhead();

        $this->assertInstanceOf(MaidenheadConverter::class, $mhConvertor);
    }
}
