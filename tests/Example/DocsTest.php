<?php

namespace PhpTui\Tui\Tests\Example;

use Generator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DocsTest extends TestCase
{
    /**
     * @dataProvider provideExamples
     */
    public function testExamples(string $path): void
    {
        $spec = [
            1 => ['pipe', 'w'],
        ];
        $process = proc_open(
            command: [
                PHP_BINARY,
                $path,
            ],
            descriptor_spec: $spec,
            pipes: $pipes,
            cwd: __DIR__ . '/../../',
            env_vars: [
                'LINES' => 5,
                'COLUMNS' => 10,
            ],
        );
        if (!is_resource($process)) {
            throw new RuntimeException(sprintf(
                'Could not spawn process'
            ));
        }
        $output = explode("\n", (string)stream_get_contents($pipes[1]));
        $exitCode = proc_close($process);
        // TODO: parse the output and dump it somewhere to be used in the docs
        self::assertEquals(0, $exitCode);

    }
    /**
     * @return Generator<array{string}>
     */
    public static function provideExamples(): Generator
    {
        foreach ((array)glob(__DIR__ . '/../../example/docs/*/*.php') as $example) {
            if (false === $example) {
                continue;
            }
            yield [
                $example,
            ];
        }
    }
}
