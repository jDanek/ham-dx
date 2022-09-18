<?php

declare(strict_types=1);

namespace HamDx\Factory;

use HamDx\Comparator\Comparator;
use Location\Bearing\BearingEllipsoidal;
use Location\Bearing\BearingSpherical;
use Location\Distance\Haversine;
use Location\Distance\Vincenty;

class ComparatorFactory
{

    /**
     * Create a faster comparator (distance: Haversine formula, bearing: Spherical Earth model)
     */
    public function createFaster(): Comparator
    {
        return new Comparator(new Haversine(), new BearingSpherical());
    }

    /**
     * Create a precise comparator (distance: Vincenty formula, bearing: Ellipsoidal Earth model)
     */
    public function createPrecise(): Comparator
    {
        return new Comparator(new Vincenty(), new BearingEllipsoidal());
    }
}
