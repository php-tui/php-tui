<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model\Widget;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Widget\SpanParser;
use PHPUnit\Framework\TestCase;

class SpanParserTest extends TestCase
{
    public function testParseOneTag(): void
    {
        $spans = SpanParser::new()->parse('<fg=green bg=blue options=bold,italic>Hello</> World');
        self::assertCount(2, $spans);

        $firstSpan = $spans[0];
        self::assertSame('Hello', $firstSpan->content);
        self::assertSame(AnsiColor::Green, $firstSpan->style->fg);
        self::assertSame(AnsiColor::Blue, $firstSpan->style->bg);
        self::assertTrue(($firstSpan->style->addModifiers & Modifier::BOLD) === Modifier::BOLD);
        self::assertTrue(($firstSpan->style->addModifiers & Modifier::ITALIC) === Modifier::ITALIC);

        $secondSpan = $spans[1];
        self::assertSame(' World', $secondSpan->content);
        self::assertNull($secondSpan->style->fg);
        self::assertNull($secondSpan->style->bg);
        self::assertSame($secondSpan->style->addModifiers, Modifier::NONE);
        self::assertSame($secondSpan->style->subModifiers, Modifier::NONE);
    }

    public function testParseNestedTags(): void
    {
        $spans = SpanParser::new()->parse('<fg=green bg=blue options=bold,italic>Hello <fg=white bg=red>Wor</>ld</> PHP');

        self::assertCount(4, $spans);

        $firstSpan = $spans[0];
        self::assertSame('Hello ', $firstSpan->content);
        self::assertSame(AnsiColor::Green, $firstSpan->style->fg);
        self::assertSame(AnsiColor::Blue, $firstSpan->style->bg);
        self::assertTrue(($firstSpan->style->addModifiers & Modifier::BOLD) === Modifier::BOLD);
        self::assertTrue(($firstSpan->style->addModifiers & Modifier::ITALIC) === Modifier::ITALIC);

        $secondSpan = $spans[1];
        self::assertSame('Wor', $secondSpan->content);
        self::assertSame(AnsiColor::Red, $secondSpan->style->bg);
        self::assertSame(AnsiColor::White, $secondSpan->style->fg);
        self::assertTrue(($secondSpan->style->addModifiers & Modifier::BOLD) === Modifier::BOLD);
        self::assertTrue(($secondSpan->style->addModifiers & Modifier::ITALIC) === Modifier::ITALIC);

        $thirdSpan = $spans[2];
        self::assertSame('ld', $thirdSpan->content);
        self::assertSame(AnsiColor::Blue, $thirdSpan->style->bg);
        self::assertSame(AnsiColor::Green, $thirdSpan->style->fg);
        self::assertTrue(($thirdSpan->style->addModifiers & Modifier::BOLD) === Modifier::BOLD);
        self::assertTrue(($thirdSpan->style->addModifiers & Modifier::ITALIC) === Modifier::ITALIC);

        $fourthSpan = $spans[3];
        self::assertSame(' PHP', $fourthSpan->content);
        self::assertNull($fourthSpan->style->fg);
        self::assertNull($fourthSpan->style->bg);
        self::assertSame($fourthSpan->style->addModifiers, Modifier::NONE);
        self::assertSame($fourthSpan->style->subModifiers, Modifier::NONE);
    }

    public function testParseAngleBrackets(): void
    {
        $spans = SpanParser::new()->parse('<bg=white><fg=blue>PHP</> <fg=red><></> <fg=yellow>Rust</></> 1 > 2 && 2 < 3');
        self::assertCount(6, $spans);

        $firstSpan = $spans[0];
        self::assertSame('PHP', $firstSpan->content);
        self::assertSame(AnsiColor::Blue, $firstSpan->style->fg);
        self::assertSame(AnsiColor::White, $firstSpan->style->bg);

        $secondSpan = $spans[1];
        self::assertSame(' ', $secondSpan->content);
        self::assertNull($secondSpan->style->fg);
        self::assertSame(AnsiColor::White, $secondSpan->style->bg);

        $thirdSpan = $spans[2];
        self::assertSame('<>', $thirdSpan->content);
        self::assertSame(AnsiColor::Red, $thirdSpan->style->fg);
        self::assertSame(AnsiColor::White, $thirdSpan->style->bg);

        $fourthSpan = $spans[3];
        self::assertSame(' ', $fourthSpan->content);
        self::assertNull($fourthSpan->style->fg);
        self::assertSame(AnsiColor::White, $fourthSpan->style->bg);

        $fifthSpan = $spans[4];
        self::assertSame('Rust', $fifthSpan->content);
        self::assertSame(AnsiColor::Yellow, $fifthSpan->style->fg);
        self::assertSame(AnsiColor::White, $fifthSpan->style->bg);

        $sixthSpan = $spans[5];
        self::assertSame(' 1 > 2 && 2 < 3', $sixthSpan->content);
        self::assertNull($sixthSpan->style->fg);
        self::assertNull($sixthSpan->style->bg);
    }

    public function testParseWithBreakLine(): void
    {
        $spans = SpanParser::new()->parse("<fg=blue>PHP</>\n<fg=yellow>Rust</>");
        self::assertCount(3, $spans);

        $firstSpan = $spans[0];
        self::assertSame('PHP', $firstSpan->content);
        self::assertSame(AnsiColor::Blue, $firstSpan->style->fg);
        self::assertNull($firstSpan->style->bg);

        $secondSpan = $spans[1];
        self::assertSame("\n", $secondSpan->content);
        self::assertNull($secondSpan->style->fg);
        self::assertNull($secondSpan->style->bg);

        $thirdSpan = $spans[2];
        self::assertSame('Rust', $thirdSpan->content);
        self::assertSame(AnsiColor::Yellow, $thirdSpan->style->fg);
        self::assertNull($thirdSpan->style->bg);
    }

    public function testParseWithDuplicateClosingTags(): void
    {
        $spans = SpanParser::new()->parse('<fg=green>Hello</>World</></>');
        self::assertCount(2, $spans);

        $firstSpan = $spans[0];
        self::assertSame('Hello', $firstSpan->content);
        self::assertSame(AnsiColor::Green, $firstSpan->style->fg);

        $secondSpan = $spans[1];
        self::assertSame('World', $secondSpan->content);
        self::assertNull($secondSpan->style->fg);
    }

    public function testParseHandlingOfEscapedTags(): void
    {
        $spans = SpanParser::new()->parse('<fg=green>Hello \<strong class="foo">some info\</strong> World</>');
        self::assertCount(1, $spans);
        $firstSpan = $spans[0];
        self::assertSame('Hello <strong class="foo">some info</strong> World', $firstSpan->content);
        self::assertSame(AnsiColor::Green, $firstSpan->style->fg);

        $spans = SpanParser::new()->parse('<fg=green>Hello \<strong class="foo"\>some info\</strong\> World</>');
        self::assertCount(1, $spans);
        $firstSpan = $spans[0];
        self::assertSame('Hello <strong class="foo">some info</strong> World', $firstSpan->content);
        self::assertSame(AnsiColor::Green, $firstSpan->style->fg);
    }

    public function testParseWithEmptyParameters(): void
    {
        $spans = SpanParser::new()->parse('<fg = >Hello <options>World</></>');
        self::assertCount(2, $spans);

        $firstSpan = $spans[0];
        self::assertSame('Hello ', $firstSpan->content);
        self::assertNull($firstSpan->style->fg);
        self::assertNull($firstSpan->style->bg);

        $secondSpan = $spans[1];
        self::assertSame('World', $secondSpan->content);
        self::assertNull($secondSpan->style->fg);
        self::assertNull($secondSpan->style->bg);
    }

    public function testParseEmptyString(): void
    {
        $spans = SpanParser::new()->parse('');
        self::assertCount(0, $spans);
    }
}
