<?php

declare(strict_types=1);

namespace HamDx\Validation;

class MaidenheadValidator implements ValidatorInterface
{
    /** @var string HTML input validation pattern */
    public const PATTERN_PLAIN = '[a-rA-R]{2}(?:(?:[0-9]{2}[a-xA-X]{2})+(?:[0-9]{2})?|[0-9]{2})?';

    /**
     * @param int|null &$errorPosition reference variable to obtain the approximate position of the error if validation fails
     */
    public function validate(string $callsign, int &$errorPosition = null): bool
    {
        $result = preg_match('{^' . self::PATTERN_PLAIN . '}i', $callsign, $matches);

        // not match
        if (!$result) {
            $errorPosition = 1; // locator is erroneous from position 1, otherwise it would pass to partial match
            return false;
        }

        // partial match (mismatched characters | out of range characters | odd number of characters)
        $callsignLength = strlen($callsign);
        $matchLength = strlen($matches[0]);
        if ($callsignLength > $matchLength) {
            $errorPosition = $matchLength + 1;
            return false;
        }

        return true;
    }
}
