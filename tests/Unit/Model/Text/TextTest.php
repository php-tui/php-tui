<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Text;

use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Style\Modifier;
use PhpTui\Tui\Style\Style;
use PhpTui\Tui\Text\Text;
use PHPUnit\Framework\TestCase;

final class TextTest extends TestCase
{
    public function testRaw(): void
    {
        $text = Text::fromString("The first line\nThe second line");
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

    public function testParse(): void
    {
        $text = Text::parse("<fg=blue;bg=white;options=bold,italic>Hello</>\n<fg=white;bg=green;options=italic>World</>");
        self::assertCount(2, $text->lines);

        $firstLine = $text->lines[0];
        self::assertCount(1, $firstLine->spans);
        self::assertEquals(AnsiColor::Blue, $firstLine->spans[0]->style->fg);
        self::assertEquals(AnsiColor::White, $firstLine->spans[0]->style->bg);
        self::assertTrue(($firstLine->spans[0]->style->addModifiers & Modifier::BOLD) === Modifier::BOLD);
        self::assertTrue(($firstLine->spans[0]->style->addModifiers & Modifier::ITALIC) === Modifier::ITALIC);

        $secondLine = $text->lines[1];
        self::assertCount(1, $secondLine->spans);
        self::assertEquals(AnsiColor::White, $secondLine->spans[0]->style->fg);
        self::assertEquals(AnsiColor::Green, $secondLine->spans[0]->style->bg);
        self::assertTrue(($secondLine->spans[0]->style->addModifiers & Modifier::BOLD) === Modifier::NONE);
        self::assertTrue(($secondLine->spans[0]->style->addModifiers & Modifier::ITALIC) === Modifier::ITALIC);
    }

    public function testParseTextWithBreakLines(): void
    {
        // This test is to ensure that the text is parsed correctly when it contains line breaks inside and outside the tags.
        $text = Text::parse("<fg=blue;bg=white;options=bold,italic>Hel\nlo</>\n<fg=white;bg=green;options=italic>Wor\nld</>");
        // Should be parsed into two new lines.
        self::assertCount(2, $text->lines);

        $firstLine = $text->lines[0];
        self::assertSame("Hel\nlo", $firstLine->spans[0]->content);
        self::assertCount(1, $firstLine->spans);
        self::assertEquals(AnsiColor::Blue, $firstLine->spans[0]->style->fg);
        self::assertEquals(AnsiColor::White, $firstLine->spans[0]->style->bg);
        self::assertTrue(($firstLine->spans[0]->style->addModifiers & Modifier::BOLD) === Modifier::BOLD);
        self::assertTrue(($firstLine->spans[0]->style->addModifiers & Modifier::ITALIC) === Modifier::ITALIC);

        $secondLine = $text->lines[1];
        self::assertSame("Wor\nld", $secondLine->spans[0]->content);
        self::assertCount(1, $secondLine->spans);
        self::assertEquals(AnsiColor::White, $secondLine->spans[0]->style->fg);
        self::assertEquals(AnsiColor::Green, $secondLine->spans[0]->style->bg);
        self::assertTrue(($secondLine->spans[0]->style->addModifiers & Modifier::BOLD) === Modifier::NONE);
        self::assertTrue(($secondLine->spans[0]->style->addModifiers & Modifier::ITALIC) === Modifier::ITALIC);
    }
}
