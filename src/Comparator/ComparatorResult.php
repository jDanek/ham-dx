<?php

declare(strict_types=1);

namespace HamDx\Comparator;

class ComparatorResult
{
    /** @var float */
    private $distance;
    /** @var float */
    private $bearing;
    /** @var float */
    private $finalBearing;

    /**
     * @param float $distance distance between two points
     * @param float $bearing bearing as seen from the first point
     * @param float $finalBearing bearing as seen approaching the destination point
     */
    public function __construct(float $distance, float $bearing, float $finalBearing)
    {
        $this->distance = $distance;
        $this->bearing = $bearing;
        $this->finalBearing = $finalBearing;
    }

    /**
     * Returns distance between two points
     */
    public function getDistance(): float
    {
        return $this->distance;
    }

    /**
     * Returns bearing as seen from the first point
     */
    public function getBearing(): float
    {
        return $this->bearing;
    }

    /**
     * Returns bearing as seen approaching the destination point
     */
    public function getFinalBearing(): float
    {
        return $this->finalBearing;
    }
}
