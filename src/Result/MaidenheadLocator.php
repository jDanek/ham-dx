<?php

declare(strict_types=1);

namespace HamDx\Result;

class MaidenheadLocator extends ValidLocator
{
    public function getCallsign(int $length = 10): string
    {
        if (($length % 2) !== 0) {
            throw new \LogicException('The length must be an even number.');
        }

        $callsign = parent::getCallsign();
        $callsignLength = strlen($callsign);

        // length correction
        if ($length < 2) {
            $length = 2;
        }
        if ($length > $callsignLength) {
            $length = $callsignLength;
        }

        return substr($callsign, 0, $length);
    }
}
