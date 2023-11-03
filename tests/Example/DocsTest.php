<?php

namespace PhpTui\Tui\Tests\Example;

use Generator;
use PHPUnit\Framework\TestCase;
use PhpTui\Term\AnsiParser;
use PhpTui\Term\Painter\HtmlStylePainter;
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
                'LINES' => 20,
                'COLUMNS' => 50,
            ],
        );
        if (!is_resource($process)) {
            throw new RuntimeException(sprintf(
                'Could not spawn process'
            ));
        }
        $output = (string)stream_get_contents($pipes[1]);
        $exitCode = proc_close($process);
        self::assertEquals(0, $exitCode);

        $actions = AnsiParser::parseString($output, throw: false);

        $painter = HtmlStylePainter::default(50, 20);
        $painter->paint($actions);
        $output = $painter->toString();

        $snapshot = substr($path, 0, -3) . 'snapshot';
        if (!file_exists($snapshot) || getenv('SNAPSHOT_APPROVE')) {
            file_put_contents($snapshot, $output);
            return;
        }
        $existing = file_get_contents($snapshot);
        self::assertEquals($output, $existing);

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
            yield dirname($example) . ' ' . basename($example) => [
                $example,
            ];
        }
    }
}
