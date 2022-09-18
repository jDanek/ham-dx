<?php

declare(strict_types=1);

namespace HamDx\Result;

use Location\Coordinate;
use Location\Formatter\Coordinate\FormatterInterface;

class ValidLocator extends Locator
{
    public function __construct(string $callsign, Coordinate $coordinate)
    {
        parent::__construct($callsign, $coordinate);
    }

    public function format(FormatterInterface $formatter): string
    {
        return $this->coordinate->format($formatter);
    }
}
