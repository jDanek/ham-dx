<?php

declare(strict_types=1);

namespace HamDx\Result;

use Location\Coordinate;

abstract class Locator
{
    /** @var string */
    protected $callsign;
    /** @var Coordinate */
    protected $coordinate;

    public function __construct(
        ?string     $callsign,
        ?Coordinate $coordinate
    )
    {
        $this->callsign = $callsign;
        $this->coordinate = $coordinate;
    }

    public function getCallsign(): string
    {
        return $this->callsign;
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

}
