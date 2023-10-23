<?php

namespace PhpTui\Tui\Tests\Model\Widget;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    public function testRaw(): void
    {
        $text = Text::raw("The first line\nThe second line");
        self::assertCount(2, $text->lines);
    }

    public function testStyled(): void
    {
        $style = Style::default();
        $style->fg = AnsiColor::Red;
        $text = Text::styled("The first line\nThe second line", $style);
        self::assertCount(2, $text->lines);
        self::assertCount(1, $text->lines[0]->spans);
        self::assertEquals(AnsiColor::Red, $text->lines[0]->spans[0]->style->fg);
    }
}
