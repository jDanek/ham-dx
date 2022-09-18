<?php

namespace HamDx\Validation;

use PHPUnit\Framework\TestCase;

class MaidenheadAnalyzerTest extends TestCase
{
    /** @var MaidenheadAnalyzer */
    private $analyzer;

    public function setUp(): void
    {
        parent::setUp();
        $this->analyzer = new MaidenheadAnalyzer(new MaidenheadValidator());
    }

    public function testAnalyze(): void
    {
        $result = $this->analyzer->analyze('rz12ab');
        var_dump($result);
    }
}
