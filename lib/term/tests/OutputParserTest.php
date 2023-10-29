<?php

namespace PhpTui\Term\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use PhpTui\Term\Action;
use PhpTui\Term\Actions;
use PhpTui\Term\Colors;
use PhpTui\Term\OutputParser;

class OutputParserTest extends TestCase
{
    /**
     * @param Action[] $expected
     * @param string[] $chunks
     * @dataProvider provideParse
     */
    public function testParse(array $chunks, array $expected): void
    {
        $parser = new OutputParser(throw: true);
        foreach ($chunks as $i => $line) {
            $parser->advance($line, $i === count($chunks) - 1);
        }

        self::assertEquals($expected, $parser->drain());
    }

    /**
     * @return Generator<string,array{string[],Action[]}>
     */
    public static function provideParse(): Generator
    {
        yield 'set bg color' => [
            ["\033[48;5;2m"],
            [
                Actions::setRgbBackgroundColor(0, 128, 0), // green
            ]
        ];

        yield 'set fg color' => [
            ["\033[38;5;4m"],
            [
                Actions::setRgbForegroundColor(0, 0, 128)
            ],
        ];

        yield 'set rgb bg color' => [
            ["\033[48;2;2;3;4m"],
            [
                Actions::setRgbBackgroundColor(2, 3, 4)
            ],
        ];

        yield 'set rgb fg color' => [
            ["\033[38;2;2;3;4m"],
            [
                Actions::setRgbForegroundColor(2, 3, 4),
            ],
        ];

        yield 'cursor hide' => [
            ["\033[?25l"],
            [
                Actions::cursorHide(),
            ],
        ];

        yield 'cursor show' => [
            ["\033[?25h"],
            [
                Actions::cursorShow(),
            ],
        ];

        yield 'alternate screen enable' => [
            ["\033[?1049h"],
            [
                Actions::alternateScreenEnable(),
            ],
        ];
        yield 'alternate screen disable' => [
            ["\033[?1049l"],
            [
                Actions::alternateScreenDisable(),
            ],
        ];

        yield 'move cursor' => [
            ["\033[2;3H"],
            [
                Actions::moveCursor(2, 3),
            ],
        ];

        yield 'reset' => [
            ["\033[0m"],
            [
                Actions::reset(),
            ],
        ];

        yield 'text' => [
            ["\033[0mHello World\033[2;3HGood"],
            [
                Actions::reset(),
                Actions::printString('Hello World'),
                Actions::moveCursor(2,3),
                Actions::printString('Good'),
            ],
        ];
    }
}

