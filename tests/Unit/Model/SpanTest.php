<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model;

use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Span;
use PHPUnit\Framework\TestCase;

class SpanTest extends TestCase
{
    public function testToStyledGraphemes(): void
    {
        $span = Span::fromString('Hello')->blue();

        $baseStyle = Style::default()->fg(AnsiColor::Red);
        $styledGraphemes = $span->toStyledGraphemes($baseStyle);

        self::assertCount(5, $styledGraphemes);

        foreach ($styledGraphemes as $i => $grapheme) {
            self::assertEquals(AnsiColor::Blue, $grapheme->style->fg);
            self::assertEquals($span->content[$i], $grapheme->symbol);
        }

        self::assertEquals(AnsiColor::Red, $baseStyle->fg);
    }
}
