<?php

declare(strict_types=1);

namespace PhpTui\Bdf\Tests\Benchmark;

use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpTui\BDF\BdfParser;
use RuntimeException;

#[Iterations(10)]
#[Revs(25)]
final class BdfParserBench
{
    private string $contents;

    public function __construct()
    {
        $contents = file_get_contents(__DIR__ . '/../../fonts/6x10_ASCII.bdf');

        if (false === $contents) {
            throw new RuntimeException('Could not read file');
        }

        $this->contents = $contents;
    }

    public function benchParseRealFont(): void
    {
        (new BdfParser())->parse($this->contents);
    }
}
