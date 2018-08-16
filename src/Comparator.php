<?php declare(strict_types=1);

namespace Toolia\HamDx;

/**
 * Class Comparator
 *
 * @author Jirka Daněk <jdanek.eu>
 */
class Comparator
{
    /**
     * Return calculated distance between two latitude and longitude points (in KM)
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    function between(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $rad = M_PI / 180;
        $lonDelta = $lon2 - $lon1;
        $dist = sin($lat1 * $rad) * sin($lat2 * $rad) + cos($lat1 * $rad) * cos($lat2 * $rad) * cos($lonDelta * $rad);

        return round(acos($dist) / $rad * 60 * 1.853);
    }

    /**
     * Return calculated azimuth from latitude and longitude
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float
     */
    function azimut(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $lonDelta = $lon2 - $lon1;
        $azimut = rad2deg(atan(sin($lonDelta) * cos($lat2) / (sin($lat2) * cos($lat1) - cos($lat2) * sin($lat1) * cos($lonDelta))));
        $azimut = ($lat1 > $lat2 ? (180.0 + $azimut) : $azimut);
        return $azimut;
    }
}
