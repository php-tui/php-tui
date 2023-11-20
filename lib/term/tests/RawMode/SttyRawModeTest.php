<?php

declare(strict_types=1);

namespace PhpTui\Term\Tests\RawMode;

use PhpTui\Term\ProcessResult;
use PhpTui\Term\ProcessRunner\ClosureRunner;
use PhpTui\Term\RawMode\SttyRawMode;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class SttyRawModeTest extends TestCase
{
    public function testDoesNothingIfAlreadyEnabled(): void
    {
        $called = [];
        $runner = ClosureRunner::new(function (array $command) use (&$called): ProcessResult {
            $called[] = $command;
            return new ProcessResult(0,'','');
        });

        $raw = SttyRawMode::new($runner);
        $raw->enable();
        $raw->enable();
        self::assertCount(3, $called);
        $raw->disable();
    }

    public function testEnableDisable(): void
    {
        $called = [];
        $runner = ClosureRunner::new(function (array $command) use (&$called): ProcessResult {
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
