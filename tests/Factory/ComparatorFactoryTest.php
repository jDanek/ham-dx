<?php

namespace HamDx\Factory;

use HamDx\Comparator\Comparator;
use PHPUnit\Framework\TestCase;

class ComparatorFactoryTest extends TestCase
{
    protected $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new ComparatorFactory();
    }

    public function testCreatePrecise()
    {
        $comparator = $this->factory->createPrecise();
        $this->assertInstanceOf(Comparator::class, $comparator);
    }

    public function testCreateFaster()
    {
        $comparator = $this->factory->createFaster();
        $this->assertInstanceOf(Comparator::class, $comparator);
    }
}
