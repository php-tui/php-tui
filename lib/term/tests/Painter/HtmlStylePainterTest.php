<?php

namespace PhpTui\Term\Tests\Painter;

use PHPUnit\Framework\TestCase;
use PhpTui\Term\Actions;
use PhpTui\Term\Painter\HtmlStylePainter;

class HtmlStylePainterTest extends TestCase
{
    public function testPaint(): void
    {
        $painter = HtmlStylePainter::default(2, 5);
        $painter->paint([
            Actions::printString('Hell'),
            Actions::moveCursor(2, 5),
            Actions::printString('Worl'),
        ]);
        self::assertEquals(
            <<<'EOT'
                <div style="font-family:monospace;color:black;display:table-cell;line-height:1em;font-size:1em">H</div><div style="font-family:monospace;color:black;display:table-cell;line-height:1em;font-size:1em">e</div><div style="clear: both;"></div>
                <div style="font-family:monospace;color:black;display:table-cell;line-height:1em;font-size:1em">l</div><div style="font-family:monospace;color:black;display:table-cell;line-height:1em;font-size:1em">l</div><div style="clear: both;"></div>
                <div style="font-family:monospace;color:black;display:table-cell;line-height:1em;font-size:1em">&nbsp;</div><div style="font-family:monospace;color:black;display:table-cell;line-height:1em;font-size:1em">&nbsp;</div><div style="clear: both;"></div>
                <div style="font-family:monospace;color:black;display:table-cell;line-height:1em;font-size:1em">W</div><div style="font-family:monospace;color:black;display:table-cell;line-height:1em;font-size:1em">o</div><div style="clear: both;"></div>
                <div style="font-family:monospace;color:black;display:table-cell;line-height:1em;font-size:1em">r</div><div style="font-family:monospace;color:black;display:table-cell;line-height:1em;font-size:1em">l</div><div style="clear: both;overflow:hidden"></div>
                EOT,
            $painter->toString()
        );
    }
}
