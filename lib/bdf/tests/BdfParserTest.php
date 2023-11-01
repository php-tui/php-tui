<?php

namespace PhpTui\Bdf\Tests;

use PHPUnit\Framework\TestCase;
use PhpTui\BDF\BdfBoundingBox;
use PhpTui\BDF\BdfCoord;
use PhpTui\BDF\BdfMetadata;
use PhpTui\BDF\BdfParser;
use PhpTui\BDF\BdfProperty;
use PhpTui\BDF\BdfSize;

class BdfParserTest extends TestCase
{
    public function testParse(): void
    {
        $font = (new BdfParser())->parse($this->font());
        
        self::assertEquals(new BdfMetadata(
            version: 2.1,
            name: '"test font"',
            pointSize: 16,
            resolution: new BdfSize(75, 75),
            boundingBox: new BdfBoundingBox(
                size: new BdfSize(16,24),
                offset: new BdfCoord(0,0),
            ),
        ), $font->metadata);

        self::assertEquals('Copyright123', $font->properties->get(BdfProperty::Copyright));
    }

    private function font(): string
    {
        return <<<EOT
            STARTFONT 2.1
            FONT "test font"
            SIZE 16 75 75
            FONTBOUNDINGBOX 16 24 0 0
            STARTPROPERTIES 3
            COMMENT   "foo"
            COPYRIGHT "Copyright123"
            FONT_ASCENT 1
            COMMENT comment
            FONT_DESCENT 2
            ENDPROPERTIES
            STARTCHAR Char 0
            ENCODING 64
            DWIDTH 8 0
            BBX 8 8 0 0
            BITMAP
            1f
            01
            ENDCHAR
            STARTCHAR Char 1
            ENCODING 65
            DWIDTH 8 0
            BBX 8 8 0 0
            BITMAP
            2f
            02
            ENDCHAR
            ENDFONT
            EOT;
    }
}
