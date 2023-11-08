<?php

namespace PhpTui\Tui\Tests\Widget;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Widget\Block;
use PhpTui\Tui\Widget\Block\Padding;
use PhpTui\Tui\Widget\PhpCode;
use Generator;

class PhpCodeTest extends WidgetTestCase
{
    /**
     * @dataProvider providePhpCodeRender
     * @param array<int,string> $expected
     */
    public function testPhpCodeRender(Area $area, Widget $widget, array $expected): void
    {
        $buffer = Buffer::empty($area);
        $this->render($buffer, $widget);
        self::assertEquals($expected, $buffer->toLines());
    }
    /**
     * @return Generator<array{Area,Widget,list<string>}>
     */
    public static function providePhpCodeRender(): Generator
    {
        yield 'write to buffer' => [
            Area::fromDimensions(10, 10),
            new PhpCode(code: '<?php echo "Hello World";'),
            [
                '<?php echo',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
                '          ',
            ]
           ,
        ];
        yield 'write to buffer multiple' => [
            Area::fromDimensions(20, 4),
            new PhpCode(code: <<<'EOT'
            <?php
            if (true) {
                echo "Hello World";

            }
            EOT
            ),
            [
                '<?php',
                '              ',
                'if (true) {',
                '        ',
                '    echo "Hello Worl',
                '                    ',
                '}"                  '
            ]
           ,
        ];
    }
}
