<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget\Span;
use PHPUnit\Framework\TestCase;

class SpanTest extends TestCase
{
    public function testToStyledGraphemes(): void
    {
        $baseStyle = Style::default()->fg(AnsiColor::Red);
        $span = Span::styled('Hello', Style::default()->fg(AnsiColor::Blue));

        $styledGraphemes = $span->toStyledGraphemes($baseStyle);
        for ($i = 0; $i < count($styledGraphemes); $i++) {
            $grapheme = $styledGraphemes[$i];
            self::assertEquals(AnsiColor::Red, $grapheme->style->fg);
            self::assertEquals($span->content[$i], $grapheme->symbol);
        }

        self::assertEquals(AnsiColor::Red, $baseStyle->fg);
    }
}
