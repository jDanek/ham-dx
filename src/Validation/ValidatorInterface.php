<?php

declare(strict_types=1);

namespace HamDx\Validation;

interface ValidatorInterface
{
    /**
     * @param int|null &$errorPosition reference variable to obtain the approximate position of the error if validation fails
     */
    public function validate(string $callsign, int &$errorPosition = null): bool;
}
