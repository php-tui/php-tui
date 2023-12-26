<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Extension\Core\Widget\Paragraph\Wrap;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Text\Line;
use PhpTui\Tui\Text\Text;
use PhpTui\Tui\Widget\HorizontalAlignment;

final class ParagraphRendererTest extends WidgetTestCase
{
    public function testFromString(): void
    {
        $paragraph = ParagraphWidget::fromString('Hello');
        self::assertEquals(ParagraphWidget::fromText(Text::fromString('Hello')), $paragraph);
    }

    public function testFromMultilineString(): void
    {
        $paragraph = ParagraphWidget::fromString("Hello\nGoodbye");
        self::assertEquals(ParagraphWidget::fromLines(
            Line::fromString('Hello'),
            Line::fromString('Goodbye'),
        ), $paragraph);
        $area = Area::fromScalars(0, 0, 10, 2);
        $buffer = Buffer::empty($area);
        $this->render($buffer, $paragraph);
        self::assertEquals([
            'Hello     ',
            'Goodbye   ',
        ], $buffer->toLines());
    }

    /**
     * @dataProvider provideParagraph
     */
    public function testParagraph(
        Area $area,
        ParagraphWidget $paragraph,
        string $expected
    ): void {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $paragraph);
        self::assertEquals($expected, $buffer->toString());
    }

    /**
     * @return Generator<string,array{Area,ParagraphWidget,string}>
     */
    public static function provideParagraph(): Generator
    {
        yield 'simple' => [
            Area::fromDimensions(8, 1),
            ParagraphWidget::fromText(
                Text::fromString('Gday')
            )->wrap(Wrap::Character),
            'Gday    ',
        ];
        yield 'wrap' => [
            Area::fromDimensions(8, 3),
            ParagraphWidget::fromText(
                Text::fromString('Gday mate lets put another shrimp on the barby')
            )->wrap(Wrap::Character),
            implode("\n", [
                'Gday mat',
                'e lets p',
                'ut anoth',
            ]),
        ];
        yield 'align right' => [
            Area::fromDimensions(8, 1),
            ParagraphWidget::fromText(
                Text::fromLine(
                    Line::fromString('Gday')->alignment(HorizontalAlignment::Right)
                )
            )->wrap(Wrap::Character),
            '    Gday',
        ];
        yield 'align left and right' => [
            Area::fromDimensions(10, 1),
            ParagraphWidget::fromLines(
                Line::fromString('1/1')->alignment(HorizontalAlignment::Left),
                Line::fromString('About')->alignment(HorizontalAlignment::Right),
            )->wrap(Wrap::Word),
            '1/1       ',
            '     About',
        ];
        yield 'line with unicode wrapped by character' => [
            Area::fromDimensions(14, 1),
            ParagraphWidget::fromText(
                Text::fromString('こんにちは, 世界! 😃')
            )->wrap(Wrap::Character),
            'こんにちは, 世',
        ];
        yield 'line with lorem ipsum wrapped by character' => [
            Area::fromDimensions(18, 3),
            ParagraphWidget::fromText(
                Text::fromString('Lorem ipsum dolor sit amet, consectetur')
            )->wrap(Wrap::Character),
            implode("\n", [
                'Lorem ipsum dolor ',
                'sit amet, consecte',
                'tur               ',
            ]),
        ];
        yield 'line with lorem ipsum wrapped by word' => [
            Area::fromDimensions(18, 3),
            ParagraphWidget::fromText(
                Text::fromString('Lorem ipsum dolor sit amet, consectetur')
            )->wrap(Wrap::Word),
            implode("\n", [
                'Lorem ipsum dolor ',
                'sit amet,         ',
                'consectetur       ',
            ]),
        ];
        yield 'line with hello wrapped by word' => [
            Area::fromDimensions(10, 2),
            ParagraphWidget::fromText(
                Text::fromString('Hello Goodbye')
            )->wrap(Wrap::Word),
            implode("\n", [
                'Hello     ',
                'Goodbye   ',
            ]),
        ];
        yield 'line with hello wrapped by character' => [
            Area::fromDimensions(10, 2),
            ParagraphWidget::fromText(
                Text::fromString('Hello Goodbye')
            )->wrap(Wrap::Character),
            implode("\n", [
                'Hello Good',
                'bye       ',
            ]),
        ];
        yield 'line with welcome to the PHP-TUI 1' => [
            Area::fromDimensions(15, 3),
            ParagraphWidget::fromText(
                Text::fromString('Welcome to the PHP-TUI 🐘 application.'),
            )->wrap(Wrap::Word),
            implode("\n", [
                'Welcome to the ',
                'PHP-TUI 🐘     ',
                'application.   ',
            ]),
        ];
        yield 'line with welcome to the PHP-TUI 2' => [
            Area::fromDimensions(8, 4),
            ParagraphWidget::fromText(
                Text::fromString('Welcome to the PHP-TUI 🐘'),
            )->wrap(Wrap::Word),
            implode("\n", [
                'Welcome ',
                'to the  ',
                'PHP-TUI ',
                '🐘      ',
            ]),
        ];
        yield 'line with multiple a letters preserving spaces' => [
            Area::fromDimensions(20, 2),
            ParagraphWidget::fromText(
                Text::fromString('AAAAAAAAAAAAAAAAAAAA    AAA'),
            )->wrap(Wrap::Word),
            implode("\n", [
                'AAAAAAAAAAAAAAAAAAAA',
                '   AAA              ',
            ]),
        ];
        yield 'line with multiple a letters while not preserving spaces' => [
            Area::fromDimensions(20, 2),
            ParagraphWidget::fromText(
                Text::fromString('AAAAAAAAAAAAAAAAAAAA    AAA'),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'AAAAAAAAAAAAAAAAAAAA',
                'AAA                 ',
            ]),
        ];
        yield 'line with multiple words wrapped by character' => [
            Area::fromDimensions(20, 5),
            ParagraphWidget::fromText(
                Text::fromString('abcd efghij klmnopabcd efgh ijklmnopabcdefg hijkl mnopab c d e f g h i j k l m n o'),
            )->wrap(Wrap::Character),
            implode("\n", [
                'abcd efghij klmnopab',
                'cd efgh ijklmnopabcd',
                'efg hijkl mnopab c d',
                ' e f g h i j k l m n',
                ' o                  ',
            ]),
        ];
        yield 'line with multiple words and single spaces' => [
            Area::fromDimensions(20, 5),
            ParagraphWidget::fromText(
                Text::fromString('abcd efghij klmnopabcd efgh ijklmnopabcdefg hijkl mnopab c d e f g h i j k l m n o'),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'abcd efghij         ',
                'klmnopabcd efgh     ',
                'ijklmnopabcdefg     ',
                'hijkl mnopab c d e f',
                'g h i j k l m n o   ',
            ]),
        ];
        yield 'line with multiple words and multiple spaces' => [
            Area::fromDimensions(20, 5),
            ParagraphWidget::fromText(
                Text::fromString('abcd efghij    klmnopabcd efgh     ijklmnopabcdefg hijkl mnopab c d e f g h i j k l m n o'),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'abcd efghij         ',
                'klmnopabcd efgh     ',
                'ijklmnopabcdefg     ',
                'hijkl mnopab c d e f',
                'g h i j k l m n o   ',
            ]),
        ];
        yield 'line with short words' => [
            Area::fromDimensions(4, 3),
            ParagraphWidget::fromText(
                Text::fromString('abcd efghij'),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'abcd',
                'efgh',
                'ij  ',
            ]),
        ];
        yield 'line with with single space' => [
            Area::fromDimensions(5, 2),
            ParagraphWidget::fromText(
                Text::fromString('hello world'),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'hello',
                'world',
            ]),
        ];
        yield 'line with with multiple spaces by word' => [
            Area::fromDimensions(5, 7),
            ParagraphWidget::fromText(
                Text::fromString('hello                           world '),
            )->wrap(Wrap::Word),
            implode("\n", [
                'hello',
                '     ',
                '     ',
                '     ',
                '     ',
                '  wor',
                'ld   ',
            ]),
        ];
        yield 'line with with multiple spaces by word trimmed' => [
            Area::fromDimensions(5, 3),
            ParagraphWidget::fromText(
                Text::fromString('hello                           world '),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'hello',
                '     ',
                'world',
            ]),
        ];
        yield 'line with max line width 1' => [
            Area::fromDimensions(1, 1),
            ParagraphWidget::fromText(
                Text::fromString('abcd efghij klmnopabcd efgh ijklmnopabcdefg hijkl mnopab '),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'a',
            ]),
        ];
        yield 'line with max line width 1 with double width characters' => [
            Area::fromDimensions(1, 5),
            ParagraphWidget::fromText(
                Text::fromString("コンピュータ上で文字を扱う場合、典型的には文字\naaa\naによる通信を行う場合にその両端点では、"),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                ' ',
                'a',
                'a',
                'a',
                'a',
            ]),
        ];
        yield 'line with single char with multiples spaces' => [
            Area::fromDimensions(20, 2),
            ParagraphWidget::fromText(
                Text::fromString('a                                                                     '),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'a                   ',
                '                    ',
            ]),
        ];
        yield 'line with short lines' => [
            Area::fromDimensions(20, 7),
            ParagraphWidget::fromText(
                Text::fromString("abcdefg\nhijklmno\npabcdefg\nhijklmn\nopabcdefghijk\nlmnopabcd\n\n\nefghijklmno"),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'abcdefg             ',
                'hijklmno            ',
                'pabcdefg            ',
                'hijklmn             ',
                'opabcdefghijk       ',
                'lmnopabcd           ',
                'efghijklmno         ',
            ]),
        ];
        yield 'line with long word' => [
            Area::fromDimensions(20, 4),
            ParagraphWidget::fromText(
                Text::fromString('abcdefghijklmnopabcdefghijklmnopabcdefghijklmnopabcdefghijklmno'),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'abcdefghijklmnopabcd',
                'efghijklmnopabcdefgh',
                'ijklmnopabcdefghijkl',
                'mno                 ',
            ]),
        ];
        yield 'line with mixed length words' => [
            Area::fromDimensions(20, 5),
            ParagraphWidget::fromText(
                Text::fromString('abcd efghij klmnopabcdefghijklmnopabcdefghijkl mnopab cdefghi j klmno'),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'abcd efghij         ',
                'klmnopabcdefghijklmn',
                'opabcdefghijkl      ',
                'mnopab cdefghi j    ',
                'klmno               ',
            ]),
        ];
        yield 'line with double width chars' => [
            Area::fromDimensions(20, 5),
            ParagraphWidget::fromText(
                Text::fromString('コンピュータ上で文字を扱う場合、典型的には文字による通信を行う場合にその両端点では、'),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'コンピュータ上で文字',
                'を扱う場合、典型的に',
                'は文字による通信を行',
                'う場合にその両端点で',
                'は、                ',
            ]),
        ];
        yield 'line with double width chars with spaces' => [
            Area::fromDimensions(20, 6),
            ParagraphWidget::fromText(
                Text::fromString('コンピュ ータ上で文字を扱う場合、 典型的には文 字による 通信を行 う場合にその両端点では、'),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                'コンピュ            ',
                'ータ上で文字を扱う場',
                '合、 典型的には文   ',
                '字による 通信を行   ',
                'う場合にその両端点で',
                'は、                ',
            ]),
        ];
        yield 'line with indentation preserved' => [
            Area::fromDimensions(10, 6),
            ParagraphWidget::fromText(
                Text::fromString("               4 Indent\n                 must wrap!"),
            )->wrap(Wrap::Word),
            implode("\n", [
                '          ',
                '    4     ',
                'Indent    ',
                '          ',
                '      must',
                'wrap!     ',
            ]),
        ];
        yield 'line with indentation not preserved' => [
            Area::fromDimensions(10, 3),
            ParagraphWidget::fromText(
                Text::fromString("               4 Indent\n                 must wrap!"),
            )->wrap(Wrap::WordTrimmed),
            implode("\n", [
                '4 Indent  ',
                '          ',
                'must wrap!',
            ]),
        ];
    }
}
