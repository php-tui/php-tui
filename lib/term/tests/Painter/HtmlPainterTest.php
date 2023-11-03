<?php

namespace PhpTui\Term\Tests\Painter;

use PHPUnit\Framework\TestCase;
use PhpTui\Term\Actions;
use PhpTui\Term\Painter\HtmlPainter;

class HtmlPainterTest extends TestCase
{
    public function testPaint(): void
    {
        $painter = HtmlPainter::default(2, 5);
        $painter->paint([
            Actions::printString('Hell'),
            Actions::moveCursor(2, 5),
            Actions::printString('Worl'),
        ]);
        self::assertEquals(
            <<<'EOT'
            <div style="font-family:monospace;padding:0px;font-kerning:none;white-space:pre;display:block;float:left;line-height:1em;font-size:1em;color:white">H</div><div style="font-family:monospace;padding:0px;font-kerning:none;white-space:pre;display:block;float:left;line-height:1em;font-size:1em;color:white">e</div><div style="clear: both;"></div><div style="font-family:monospace;padding:0px;font-kerning:none;white-space:pre;display:block;float:left;line-height:1em;font-size:1em;color:white">l</div><div style="font-family:monospace;padding:0px;font-kerning:none;white-space:pre;display:block;float:left;line-height:1em;font-size:1em;color:white">l</div><div style="clear: both;"></div><div style="font-family:monospace;padding:0px;font-kerning:none;white-space:pre;display:block;float:left;line-height:1em;font-size:1em;"> </div><div style="font-family:monospace;padding:0px;font-kerning:none;white-space:pre;display:block;float:left;line-height:1em;font-size:1em;"> </div><div style="clear: both;"></div><div style="font-family:monospace;padding:0px;font-kerning:none;white-space:pre;display:block;float:left;line-height:1em;font-size:1em;color:white">W</div><div style="font-family:monospace;padding:0px;font-kerning:none;white-space:pre;display:block;float:left;line-height:1em;font-size:1em;color:white">o</div><div style="clear: both;"></div><div style="font-family:monospace;padding:0px;font-kerning:none;white-space:pre;display:block;float:left;line-height:1em;font-size:1em;color:white">r</div><div style="font-family:monospace;padding:0px;font-kerning:none;white-space:pre;display:block;float:left;line-height:1em;font-size:1em;color:white">l</div><div style="clear: both;"></div>
            EOT,
            $painter->toString()
        );
    }
}
