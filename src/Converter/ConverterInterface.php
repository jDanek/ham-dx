<?php

declare(strict_types=1);

namespace HamDx\Converter;

use Location\Coordinate;

interface ConverterInterface
{

    public function fromCoordinate(Coordinate $coordinate);

    public function toCoordinate(string $callsign): Coordinate;
}
