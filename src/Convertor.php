<?php declare(strict_types=1);

namespace Toolia\HamDx;

/**
 * Class Convertor
 *
 * @author Jirka Daněk <jdanek.eu>
 */
class Convertor
{
    const ROUND_PRECISION = 10;

    /** @var array */
    protected $valid_chars = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X'];

    /**
     * @param float $number
     * @param int $precision
     * @return float
     */
    static function roundUp(float $number, int $precision = 2): float
    {
        $fig = (int)str_pad('1', $precision + 1, '0');
        return (ceil($number * $fig) / $fig);
    }

    /**
     * @param float $number
     * @param int $precision
     * @return float
     */
    static function roundDown(float $number, int $precision = 2): float
    {
        $fig = (int)str_pad('1', $precision + 1, '0');
        return (floor($number * $fig) / $fig);
    }


    /**
     * Convert QTH Maidenhead Grid locator to lat/lon
     * Help and inspiration: http://radio.snezka.net
     *
     * @param string $locator
     * @return array [latitude, longitude]
     */
    function qthToCoords(string $locator): array
    {
        $flip = array_flip($this->valid_chars);
        $locator = strtoupper($locator);

        if (strlen($locator) === 2) $locator .= "55MM00AA"; // add center for 'field'
        if (strlen($locator) === 4) $locator .= "MM00AA"; // add center for 'square'
        if (strlen($locator) === 6) $locator .= "55AA"; // add center for 'subsquare'
        if (strlen($locator) === 8) $locator .= "MM"; // add center for 'extended subsquare'

        // calculate latitude and longitude
        $lon = -180.0;
        $lat = -90.0;

        $lon += ((int)$flip[$locator[0]] * 20) // fields
            + ((int)$locator[2] * 2) // squares
            + ((int)$flip[$locator[4]] / 12) // subsquares
            + ((int)$locator[6] / 120) // extended square
            + ((int)$flip[$locator[8]] / 2880) // extended subsquare
            + 0.000174; // center

        $lat += ((int)$flip[$locator[1]] * 10) // fields
            + ((int)$locator[3]) // squares
            + ((int)$flip[$locator[5]] / 24) // subsquares
            + ((int)$locator[7] / 240) // extended square
            + ((int)$flip[$locator[9]] / 5760) // extended subsquare
            + 0.0000868; // center

        return [$lat, $lon];
    }

    /**
     * Convert lat/lon to QTH Maidenhead Grid locator
     * Help and inspiration: http://radio.snezka.net
     *
     * @param float $lat
     * @param float $lon
     * @return string
     */
    function coordsToQth(float $lat, float $lon): string
    {
        return implode('', [
            // field
            (string)$this->valid_chars[(int)floor(($lon + 180) / 20)],
            (string)$this->valid_chars[(int)floor(($lat + 90) / 10)],
            // square
            (string)floor(fmod(($lon + 180) / 2, 10)),
            (string)floor(fmod($lat + 90, 10)),
            // subsquare
            (string)$this->valid_chars[(int)floor(fmod(($lon + 180) * 12, 24))],
            (string)$this->valid_chars[(int)floor(fmod(($lat + 90) * 24, 24))],
            // extended square
            (string)floor(fmod(($lon + 180) * 120, 10)),
            (string)floor(fmod(($lat + 90) * 240, 10)),
            // extended subsquare
            (string)$this->valid_chars[(int)floor(fmod(($lon + 180) * 2880, 24))],
            (string)$this->valid_chars[(int)floor(fmod(($lat + 90) * 5760, 24))],
        ]);
    }

    /**
     * Convert latitude and longitude location to degrees, minutes, seconds
     *
     * @param float $lat
     * @param float $lon
     * @return array [lat, lon]
     */
    function coordsToDms(float $lat, float $lon): array
    {
        return [
            $this->decimalToDms($lat),
            $this->decimalToDms($lon, false)
        ];
    }

    /**
     * Convert decimal to degrees, minutes, seconds
     *
     * @param float $decimal
     * @param bool $isLatitude
     * @return array
     */
    function decimalToDms(float $decimal, bool $isLatitude = true): array
    {
        $exploded = explode(".", (string)$decimal);
        $temp = (isset($exploded[1]) ? (float)('0.' . $exploded[1]) : 0.0);

        $temp *= 3600;
        $deg = (float)$exploded[0];
        $min = floor($temp / 60);
        $sec = floor($temp - ($min * 60));

        $direction = ($isLatitude ? ($deg < 0 ? 'S' : 'N') : ($deg < 0 ? 'W' : 'E'));

        return ['dir' => $direction, 'deg' => $deg, 'min' => $min, 'sec' => $sec];
    }

    /**
     * Convert degrees, minutes, seconds to decimal
     *
     * @param float $deg
     * @param float $min
     * @param float $sec
     * @return float
     */
    function dmsToDecimal(float $deg, float $min, float $sec): float
    {
        return static::roundUp(($deg + ((($min * 60) + ($sec)) / 3600)), static::ROUND_PRECISION);
    }

}
