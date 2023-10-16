<?php

namespace DTL\PhpTui\Tests\Widget;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Buffer;
use DTL\PhpTui\Model\Widget\Text;
use DTL\PhpTui\Widget\Paragraph;
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
            'Gday    ',
        ];
    }
}
