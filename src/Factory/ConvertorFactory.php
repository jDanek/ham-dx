<?php

declare(strict_types=1);

namespace HamDx\Factory;

use HamDx\Converter\MaidenheadConverter;
use HamDx\Validation\MaidenheadValidator;

class ConvertorFactory
{

    public function createMaidenhead(): MaidenheadConverter
    {
        $validator = new MaidenheadValidator();
        return new MaidenheadConverter($validator);
    }
}
