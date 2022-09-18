<?php

declare(strict_types=1);

namespace HamDx\Comparator;

use HamDx\Result\ValidLocator;
use Location\Bearing\BearingInterface;
use Location\Coordinate;
use Location\Distance\DistanceInterface;

class Comparator
{
    /** @var DistanceInterface */
    private $distanceCalculator;
    /** @var BearingInterface */
    private $bearingCalculator;

    public function __construct(DistanceInterface $distanceCalculator, BearingInterface $bearingCalculator)
    {
        $this->distanceCalculator = $distanceCalculator;
        $this->bearingCalculator = $bearingCalculator;
    }

    public function compareCoordinates(Coordinate $origin, Coordinate $target): ComparatorResult
    {
        return new ComparatorResult(
            $this->calculateDistance($origin, $target),
            $this->calculateBearing($origin, $target),
            $this->calculateFinalBearing($origin, $target)
        );
    }

    public function compareLocators(ValidLocator $origin, ValidLocator $target): ComparatorResult
    {
        return $this->compareCoordinates(
            $origin->getCoordinate(),
            $target->getCoordinate()
        );
    }

    public function calculateDistance(Coordinate $origin, Coordinate $target): float
    {
        return $this->distanceCalculator->getDistance($origin, $target);
    }

    /**
     * Returns bearing from origin point
     */
    public function calculateBearing(Coordinate $origin, Coordinate $target): float
    {
        return $this->bearingCalculator->calculateBearing($origin, $target);
    }

    /**
     * Returns bearing from target point
     */
    public function calculateFinalBearing(Coordinate $origin, Coordinate $target): float
    {
        return $this->bearingCalculator->calculateFinalBearing($target, $origin);
    }
}
