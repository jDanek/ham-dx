<?php

declare(strict_types=1);

namespace HamDx\Converter;

use HamDx\Validation\ValidatorInterface;
use Location\Coordinate;
use Location\Polygon;

class MaidenheadConverter implements ConverterInterface
{
    /** @var string[] */
    protected $validChars;
    /** @var ValidatorInterface */
    protected $validator;
    /** @var bool */
    protected $coordinateCentering = true;


    public function __construct(ValidatorInterface $validator)
    {
        $this->validChars = range('A', 'X');
        $this->validator = $validator;
    }

    /**
     * Set conversion of locator coordinates to the center of the square
     */
    public function setCoordinateCentering(bool $coordinateCentering): void
    {
        $this->coordinateCentering = $coordinateCentering;
    }

    public function toCoordinate(string $callsign): Coordinate
    {
        $polygon = $this->getPolygon($callsign);
        $bounds = $polygon->getBounds();

        return $this->coordinateCentering
            ? $bounds->getCenter()
            : new Coordinate($bounds->getSouth(), $bounds->getWest());
    }

    public function fromCoordinate(Coordinate $coordinate): string
    {
        $lng = $coordinate->getLng();
        $lat = $coordinate->getLat();

        $locatorParts = [
            // field
            (string)$this->validChars[(int)floor(($lng + 180) / 20)],
            (string)$this->validChars[(int)floor(($lat + 90) / 10)],
            // square
            (string)floor(fmod(($lng + 180) / 2, 10)),
            (string)floor(fmod($lat + 90, 10)),
            // subsquare
            (string)$this->validChars[(int)floor(fmod(($lng + 180) * 12, 24))],
            (string)$this->validChars[(int)floor(fmod(($lat + 90) * 24, 24))],
            // extended square
            (string)floor(fmod(($lng + 180) * 120, 10)),
            (string)floor(fmod(($lat + 90) * 240, 10)),
            // extended subsquare
            (string)$this->validChars[(int)floor(fmod(($lng + 180) * 2880, 24))],
            (string)$this->validChars[(int)floor(fmod(($lat + 90) * 5760, 24))],
        ];

        return implode('', $locatorParts);
    }

    public function getPolygon(string $locator): Polygon
    {
        // format validation
        if (!$this->validator->validate($locator)) {
            throw new \InvalidArgumentException("QTH locator '" . $locator . "' don't have the required format.");
        }

        $locator = strtoupper($locator);

        $flip = array_flip($this->validChars);
        $pairs = str_split($locator, 2);
        $pairsCount = count($pairs);

        $convert = function ($char, $isLetter) use ($flip) {
            return (int)($isLetter ? $flip[$char] : $char);
        };

        $polygon = new Polygon();

        // calculate latitude and longitude
        $lon = -180.0;
        $lat = -90.0;

        $divisor = 1;
        $count = 0;
        foreach ($pairs as $pair) {
            $count++;
            $isLetter = !is_numeric($pair);

            if ($count === 1) {
                $divisor *= 1;
            } elseif ($count % 2 === 0) {
                $divisor *= 10;
            } else {
                $divisor *= 24;
            }

            $lonDivisor = (20 / $divisor);
            $latDivisor = (10 / $divisor);

            $lon += ($convert($pair[0], $isLetter) * $lonDivisor);
            $lat += ($convert($pair[1], $isLetter) * $latDivisor);

            // last pair
            if ($pairsCount === $count) {
                $polygon->addPoint(new Coordinate($lat, $lon)); // south-west
                $polygon->addPoint(new Coordinate(($lat + $latDivisor), $lon)); // north-west
                $polygon->addPoint(new Coordinate(($lat + $latDivisor), ($lon + $lonDivisor))); // north-east
                $polygon->addPoint(new Coordinate($lat, ($lon + $lonDivisor))); // south-east
            }
        }

        return $polygon;
    }
}
