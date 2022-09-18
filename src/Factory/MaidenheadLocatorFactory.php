<?php

declare(strict_types=1);

namespace HamDx\Factory;

use HamDx\Converter\MaidenheadConverter;
use HamDx\Result\InvalidLocator;
use HamDx\Result\Locator;
use HamDx\Result\MaidenheadLocator;
use HamDx\Validation\MaidenheadValidator;
use Location\Coordinate;
use Location\Ellipsoid;
use Location\Factory\CoordinateFactory;

class MaidenheadLocatorFactory
{
    /** @var MaidenheadConverter */
    private $converter;
    /** @var MaidenheadValidator */
    private $validator;

    public function __construct()
    {
        $this->validator = new MaidenheadValidator();
        $this->converter = new MaidenheadConverter($this->validator);
    }

    public function createFromCoordinate(Coordinate $coordinate): Locator
    {
        try {
            $callsign = $this->converter->fromCoordinate($coordinate);
            return new MaidenheadLocator($callsign, $coordinate);
        } catch (\Throwable $t) {
            return new InvalidLocator(null, $coordinate);
        }
    }

    public function createFromString(string $string, Ellipsoid $ellipsoid = null): Locator
    {
        if ($this->validator->validate($string)) {
            return $this->createFromQth($string);
        }

        try {
            $coordinate = CoordinateFactory::fromString($string, $ellipsoid);
            return $this->createFromCoordinate($coordinate);
        } catch (\Throwable $t) {
            return new InvalidLocator($string, null);
        }
    }

    public function createFromLatLng(float $lat, float $lng): Locator
    {
        return $this->createFromCoordinate(new Coordinate($lat, $lng));
    }

    /**
     * @param Coordinate|string|float $input
     * @param float|null $lng only for lat/lng coordinates
     */
    public function autoDetectInput($input, float $lng = null): Locator
    {
        if ($input instanceof Coordinate) {
            return $this->createFromCoordinate($input);
        }

        if (is_float($input)) {
            if ($lng === null) {
                throw new \InvalidArgumentException('Input was detected as float, in this case the second parameter must not be null.');
            }
            return $this->createFromLatLng($input, $lng);
        }

        if (is_string($input)) {
            return $this->createFromString($input);
        }

        throw new \RuntimeException('Unsupported input format: ' . gettype($input));
    }

    private function createFromQth(string $callsign): Locator
    {
        $coordinate = $this->converter->toCoordinate($callsign);
        return new MaidenheadLocator($callsign, $coordinate);
    }
}
