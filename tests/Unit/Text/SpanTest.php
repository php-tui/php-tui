<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Text;

use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Style\Style;
use PhpTui\Tui\Text\Span;
use PHPUnit\Framework\TestCase;

final class SpanTest extends TestCase
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
