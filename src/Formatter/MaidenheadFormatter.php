<?php

declare(strict_types=1);

namespace HamDx\Formatter;

use HamDx\Converter\ConverterInterface;
use Location\Coordinate;
use Location\Formatter\Coordinate\FormatterInterface;

class MaidenheadFormatter implements FormatterInterface
{
    /** @var ConverterInterface */
    protected $converter;

    public function __construct(ConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    public function format(Coordinate $coordinate): string
    {
        return $this->converter->fromCoordinate($coordinate);
    }
}
