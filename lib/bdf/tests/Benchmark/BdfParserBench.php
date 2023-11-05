<?php

namespace PhpTui\Bdf\Tests\Benchmark;

use RuntimeException;
use PhpTui\BDF\BdfParser;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;

#[Iterations(10)]
#[Revs(25)]
final class BdfParserBench
{
    public function benchParseRealFont(): void
    {
        $contents = file_get_contents(__DIR__ . '/../../fonts/6x10.bdf');

        if (false === $contents) {
            throw new RuntimeException(
                'Could not read file'
            );
        }

        (new BdfParser())->parse($contents);
    }
}
