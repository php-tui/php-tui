<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use Generator;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
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
            ParagraphWidget::fromText(Text::fromString('Gday')),
            'Gday    ',
        ];
        yield 'wrap' => [
            Area::fromDimensions(8, 3),
            ParagraphWidget::fromText(Text::fromString('Gday mate lets put another shrimp on the barby')),
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
            ),
            '    Gday',
        ];
        yield 'align left and right' => [
            Area::fromDimensions(10, 1),
            ParagraphWidget::fromLines(
                Line::fromString('1/1')->alignment(HorizontalAlignment::Left),
                Line::fromString('About')->alignment(HorizontalAlignment::Right),
            ),
            '1/1       ',
            '     About',
        ];
    }
}
