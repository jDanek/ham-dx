<?php

declare(strict_types=1);

namespace HamDx\Validation;

class MaidenheadAnalyzer
{
    public const RANGE_FIELD = 'A-R';
    public const RANGE_SQUARE = '0-9';
    public const RANGE_SUB_SQUARE = 'A-X';

    /** @var MaidenheadValidator */
    protected $validator;

    public function __construct(MaidenheadValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param int|null $errorPosition approximate position of the error, if not known the validator is used
     */
    public function analyze(string $callsign, int $errorPosition = null): array
    {
        // if the position of the error is known, the locator cannot be valid
        $valid = ($errorPosition !== null ? false : null);

        // validation
        if ($errorPosition === null) {
            $valid = $this->validator->validate($callsign, $errorPosition);
        }

        if (!$valid) {
            $validPart = substr($callsign, 0, ($errorPosition - 1));
            $invalidPair = substr($callsign, ($errorPosition - 1), 2);

            // detect range for next pair validation
            $validRange = ((strlen($validPart) / 2) % 2 === 0 ? self::RANGE_SUB_SQUARE : self::RANGE_SQUARE);
            if ($errorPosition === 1) {
                $validRange = self::RANGE_FIELD;
            }

            $detail = [];

            // check both characters in the invalid pair
            for ($i = 0; $i < 2; $i++) {
                $currentChar = $invalidPair[$i] ?? ' '; // add space to correct for odd number of characters

                $isInvalidChar = (
                    ($validRange === self::RANGE_SQUARE && !is_numeric($currentChar))
                    || ($validRange !== self::RANGE_SQUARE
                        && (is_numeric($currentChar) || !$this->isCharInRange($currentChar, $validRange))
                    )
                );

                if ($isInvalidChar) {
                    $detail[] = [
                        'position' => $errorPosition + $i,
                        'character' => $currentChar,
                        'range' => $validRange,
                    ];
                }
            }

            return [
                'valid_part' => $validPart,
                'invalid_pair' => $invalidPair,
                'valid_range' => $validRange,
                'detail' => $detail,
            ];
        } else {
            return [];
        }
    }

    private function isCharInRange(string $char, string $range): bool
    {
        return (
            ord(strtoupper($char)) >= ord($range[0])
            && ord(strtoupper($char)) <= ord($range[2])
        );
    }
}
