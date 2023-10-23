<?php

namespace PhpTui\Tui\Tests\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Widget\Paragraph;
use Generator;
use PHPUnit\Framework\TestCase;

class ParagraphTest extends TestCase
{
    /**
     * @dataProvider provideParagraph
     */
    public function testParagraph(
        Area $area,
        Paragraph $paragraph,
        string $expected
    ): void {
        $buffer = Buffer::empty($area);
        $paragraph->render($buffer->area(), $buffer);
        self::assertEquals($expected, $buffer->toString());

    }
    /**
     * @return Generator<string,array{Area,Paragraph,string}>
     */
    public static function provideParagraph(): Generator
    {
        yield 'simple' => [
            Area::fromDimensions(8, 1),
            Paragraph::new(Text::raw('Gday')),
            'Gday    ',
        ];
        yield 'wrap' => [
            Area::fromDimensions(8, 3),
            Paragraph::new(Text::raw('Gday mate lets put another shrimp on the barby')),
            implode("\n", [
                'Gday mat',
                'e lets p',
                'ut anoth',
            ]),
        ];
    }
}
