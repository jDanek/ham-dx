<?php

declare(strict_types=1);

namespace HamDx\Util;

abstract class Util
{
    public static function cutFloat(float $float, int $places = 6): float
    {
        $format = '%.' . $places . 'F';
        return (float)sprintf($format, $float);
    }
}
