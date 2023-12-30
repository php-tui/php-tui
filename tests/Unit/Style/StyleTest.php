<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Style;

use PhpTui\Tui\Color\AnsiColor;
use PhpTui\Tui\Style\Modifier;
use PhpTui\Tui\Style\Style;
use PHPUnit\Framework\TestCase;

final class StyleTest extends TestCase
{
    public function testDefault(): void
    {
        $style = Style::default();

        self::assertNull($style->fg);
        self::assertNull($style->bg);
        self::assertNull($style->underline);
        self::assertEquals(Modifier::NONE, $style->addModifiers);
        self::assertEquals(Modifier::NONE, $style->subModifiers);
    }

    public function testFg(): void
    {
        $style = Style::default()->fg(AnsiColor::Red);

        self::assertSame(AnsiColor::Red, $style->fg);
    }

    public function testBg(): void
    {
        $style = Style::default()->bg(AnsiColor::Blue);

        self::assertSame(AnsiColor::Blue, $style->bg);
    }

    public function testAddModifier(): void
    {
        $style = Style::default()->addModifier(Modifier::BOLD);

        self::assertTrue(($style->addModifiers & Modifier::BOLD) === Modifier::BOLD);
    }

    public function testSubModifier(): void
    {
        $style = Style::default()->removeModifier(Modifier::ITALIC);

        self::assertTrue(($style->subModifiers & Modifier::ITALIC) === Modifier::ITALIC);
    }

    public function testPatch(): void
    {
        $style1 = Style::default()->bg(AnsiColor::Red);
        $style2 = Style::default()
                    ->fg(AnsiColor::Blue)
                    ->addModifier(Modifier::BOLD)
                    ->addModifier(Modifier::UNDERLINED);

        $combined = $style1->patchStyle($style2);

        self::assertEquals(Modifier::NONE, $combined->subModifiers);

        self::assertEquals(
            Modifier::BOLD | Modifier::UNDERLINED,
            $combined->addModifiers,
        );

        self::assertSame(
            (string) Style::default()->patchStyle($style1)->patchStyle($style2),
            (string) $combined
        );

        self::assertSame(AnsiColor::Blue, $combined->fg);
        self::assertSame(AnsiColor::Red, $combined->bg);

        $combined2 = Style::default()->patchStyle($combined)->patchStyle(
            Style::default()
                ->removeModifier(Modifier::BOLD)
                ->addModifier(Modifier::ITALIC),
        );

        self::assertEquals(Modifier::BOLD, $combined2->subModifiers);

        self::assertEquals(
            Modifier::ITALIC | Modifier::UNDERLINED,
            $combined2->addModifiers,
        );

        self::assertSame(AnsiColor::Blue, $combined->fg);
        self::assertSame(AnsiColor::Red, $combined->bg);
    }

    public function testToString(): void
    {
        $style = Style::default()
                    ->bg(AnsiColor::Red)
                    ->underline(AnsiColor::Blue)
                    ->addModifier(Modifier::BOLD)
                    ->removeModifier(Modifier::ITALIC)
                    ->removeModifier(Modifier::UNDERLINED);

        $expectedString = sprintf(
            'Style(fg:%s,bg: %s,u:%s,+mod:%d,-mod:%d)',
            '-',
            AnsiColor::Red->debugName(),
            AnsiColor::Blue->debugName(),
            Modifier::BOLD,
            Modifier::ITALIC | Modifier::UNDERLINED,
        );

        self::assertEquals($expectedString, (string) $style);
    }
}
