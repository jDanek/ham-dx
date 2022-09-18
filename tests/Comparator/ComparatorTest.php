<?php

namespace HamDx\Comparator;

use HamDx\Factory\ComparatorFactory;
use HamDx\Result\ValidLocator;
use Location\Coordinate;
use PHPUnit\Framework\TestCase;

class ComparatorTest extends TestCase
{
    /** @var Comparator */
    protected $fasterComparator;
    /** @var Comparator */
    protected $preciseComparator;

    protected function setUp(): void
    {
        parent::setUp();
        $factory = new ComparatorFactory();
        $this->fasterComparator = $factory->createFaster();
        $this->preciseComparator = $factory->createPrecise();
    }

    public function testCompareCoordinates()
    {
        $origin = new Coordinate(49.437587, 12.791841); // JN69JK
        $target = new Coordinate(50.645920, 13.625174); // JO60TP

        // faster
        $fResult = $this->fasterComparator->compareCoordinates($origin, $target);
        $this->assertInstanceOf(ComparatorResult::class, $fResult);
        $this->assertEquals(146947.521, $fResult->getDistance());
        $this->assertNotEquals(1111950, $fResult->getDistance());
        $this->assertEquals(23.570716358628637, $fResult->getBearing());
        $this->assertNotEquals(23.01, $fResult->getBearing());
        $this->assertEquals(203.57071635862863, $fResult->getFinalBearing());
        $this->assertNotEquals(204.7, $fResult->getFinalBearing());

        // precise
        $pResult = $this->preciseComparator->compareCoordinates($origin, $target);
        $this->assertInstanceOf(ComparatorResult::class, $pResult);
        $this->assertEquals(147060.562, $pResult->getDistance());
        $this->assertNotEquals(1116825, $pResult->getDistance());
        $this->assertEquals(23.629651127688305, $pResult->getBearing());
        $this->assertNotEquals(24.5, $pResult->getBearing());
        $this->assertEquals(203.62965112768828, $pResult->getFinalBearing());
        $this->assertNotEquals(204.2, $pResult->getFinalBearing());
    }

    public function testCompareLocators()
    {
        $origin = new Coordinate(49.437587, 12.791841); // JN69JK
        $target = new Coordinate(50.645920, 13.625174); // JO60TP
        $originLocator = new ValidLocator('JN69JK', $origin);
        $targetLocator = new ValidLocator('JO60TP', $target);

        $fResult = $this->fasterComparator->compareLocators($originLocator, $targetLocator);
        $pResult = $this->preciseComparator->compareLocators($originLocator, $targetLocator);
        $this->assertInstanceOf(ComparatorResult::class, $fResult);
        $this->assertInstanceOf(ComparatorResult::class, $pResult);
        // value tests are common with compareCoordinates()
    }

    public function testCalculateBearing()
    {
        $origin = new Coordinate(49.437587, 12.791841); // JN69JK
        $target = new Coordinate(50.645920, 13.625174); // JO60TP

        $fResult = $this->fasterComparator->calculateBearing($origin, $target);
        $pResult = $this->preciseComparator->calculateBearing($origin, $target);

        // faster
        $this->assertIsFloat($fResult);
        $this->assertEquals(23.570716358628637, $fResult);
        $this->assertNotEquals(23.6, $fResult);

        // precise
        $this->assertIsFloat($pResult);
        $this->assertEquals(23.629651127688305, $pResult);
        $this->assertNotEquals(23.7, $pResult);
    }

    public function testCalculateDistance()
    {
        $origin = new Coordinate(49.437587, 12.791841); // JN69JK
        $target = new Coordinate(50.645920, 13.625174); // JO60TP

        $fResult = $this->fasterComparator->calculateDistance($origin, $target);
        $pResult = $this->preciseComparator->calculateDistance($origin, $target);

        // faster
        $this->assertIsFloat($fResult);
        $this->assertEquals(146947.521, $fResult);
        $this->assertNotEquals(140000, $fResult);

        // precise
        $this->assertIsFloat($pResult);
        $this->assertEquals(147060.562, $pResult);
        $this->assertNotEquals(140000, $pResult);
    }

    public function testCalculateFinalBearing()
    {

        $origin = new Coordinate(49.437587, 12.791841); // JN69JK
        $target = new Coordinate(50.645920, 13.625174); // JO60TP

        $fResult = $this->fasterComparator->calculateFinalBearing($origin, $target);
        $pResult = $this->preciseComparator->calculateFinalBearing($origin, $target);

        $this->assertEquals(203.57071635862863, $fResult);
        $this->assertNotEquals(204.7, $fResult);

        $this->assertEquals(203.62965112768828, $pResult);
        $this->assertNotEquals(204.2, $pResult);
    }
}
