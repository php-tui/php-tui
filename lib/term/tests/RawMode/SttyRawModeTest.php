<?php

namespace PhpTui\Term\Tests\RawMode;

use PHPUnit\Framework\TestCase;
use PhpTui\Term\ProcessResult;
use PhpTui\Term\ProcessRunner\ClosureRunner;
use PhpTui\Term\RawMode;
use PhpTui\Term\RawMode\SttyRawMode;
use RuntimeException;

class SttyRawModeTest extends TestCase
{
    public function testEnableDisable(): void
    {
        $called = [];
        $runner = ClosureRunner::new(function (array $command) use (&$called) {
            if ($command === ['stty', '-g']) {
                $called[] = $command;
                return new ProcessResult(0, 'original mode string', '');
            }
            if ($command === ['stty', 'raw']) {
                $called[] = $command;
                return new ProcessResult(0, '', '');
            }
            if ($command === ['stty', '-echo']) {
                $called[] = $command;
                return new ProcessResult(0, '', '');
            }
            if ($command === ['stty', 'original mode string']) {
                $called[] = $command;
                return new ProcessResult(0, '', '');
            }
            throw new RuntimeException(
                sprintf('Unexpected command: %s', json_encode($command))
            );
        });

        $raw = SttyRawMode::new($runner);
        $raw->enable();
        self::assertCount(3, $called);
        $raw->disable();
        self::assertEquals([
            ['stty', '-g'],
            ['stty', 'raw'],
            ['stty', '-echo'],
            ['stty', 'original mode string'],
        ], $called);
    }
}
