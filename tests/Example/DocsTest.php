<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Example;

use Generator;
use PhpTui\Term\AnsiParser;
use PhpTui\Term\Painter\HtmlCanvasPainter;
use PhpTui\Term\Painter\StringPainter;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DocsTest extends TestCase
{
    final public const WIDTH = 20;
    final public const HEIGHT = 50;

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
                'LINES' => self::WIDTH,
                'COLUMNS' => self::HEIGHT,
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

        $painter = new StringPainter();
        $painter->paint($actions);
        $output = $painter->toString();

        $this->assertSnapshot($path, $output, 'snapshot');

        $painter = HtmlCanvasPainter::default(self::HEIGHT, self::WIDTH);
        $painter->paint($actions);
        $output = $painter->toString();

        $this->assertSnapshot($path, $output, 'html', self::normalize(...));
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

    private function assertSnapshot(string $path, string $output, string $extension, ?callable $normalizer = null): void
    {
        $snapshot = substr($path, 0, -3) . $extension;
        if ($normalizer) {
            $output = $normalizer($output);
        }
        if (!file_exists($snapshot) || getenv('SNAPSHOT_APPROVE')) {
            file_put_contents($snapshot, $output);

            return;
        }

        $existing = file_get_contents($snapshot);
        if (false === $existing) {
            throw new RuntimeException('Could not read file');
        }

        self::assertEquals($existing, $output);

    }

    private static function normalize(string $string): string
    {
        $normalized = preg_replace('{canvas id=".*?"}', 'canvas id="***"', $string);
        $normalized = preg_replace('{getElementById\(".*?"\)}', 'getElementById("***")', (string)$normalized);

        return (string)$normalized;
    }
}
