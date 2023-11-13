<?php

namespace PhpTui\Tui\Tests\Model;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Modifiers;
use PhpTui\Tui\Model\Style;
use PHPUnit\Framework\TestCase;

class StyleTest extends TestCase
{
    public function testDefault(): void
    {
        $style = Style::default();

        self::assertNull($style->fg);
        self::assertNull($style->bg);
        self::assertNull($style->underline);
        self::assertEquals(Modifiers::none()->toInt(), $style->addModifiers->toInt());
        self::assertEquals(Modifiers::none()->toInt(), $style->subModifiers->toInt());
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
        $style = Style::default()->addModifier(Modifier::Bold);

        self::assertTrue($style->addModifiers->contains(Modifier::Bold));
    }

    public function testSubModifier(): void
    {
        $style = Style::default()->removeModifier(Modifier::Italic);

        self::assertTrue($style->subModifiers->contains(Modifier::Italic));
    }

    public function testPatch(): void
    {
        $style1 = Style::default()->bg(AnsiColor::Red);
        $style2 = Style::default()
                    ->fg(AnsiColor::Blue)
                    ->addModifier(Modifier::Bold)
                    ->addModifier(Modifier::Underlined);

        $combined = $style1->patch($style2);

        self::assertEquals(
            Modifiers::none()->toInt(),
            $combined->subModifiers->toInt(),
        );

        self::assertEquals(
            Modifiers::fromInt(Modifier::Bold->value | Modifier::Underlined->value)->toInt(),
            $combined->addModifiers->toInt(),
        );

        self::assertSame(
            (string) Style::default()->patch($style1)->patch($style2),
            (string) $combined
        );

        self::assertSame(AnsiColor::Blue, $combined->fg);
        self::assertSame(AnsiColor::Red, $combined->bg);

        $combined2 = Style::default()->patch($combined)->patch(
            Style::default()
                ->removeModifier(Modifier::Bold)
                ->addModifier(Modifier::Italic),
        );

        self::assertEquals(
            Modifiers::fromModifier(Modifier::Bold)->toInt(),
            $combined2->subModifiers->toInt(),
        );

        self::assertEquals(
            Modifiers::fromInt(Modifier::Italic->value | Modifier::Underlined->value)->toInt(),
            $combined2->addModifiers->toInt(),
        );

        self::assertSame(AnsiColor::Blue, $combined->fg);
        self::assertSame(AnsiColor::Red, $combined->bg);
    }

    public function testToString(): void
    {
        $style = Style::default()
                    ->bg(AnsiColor::Red)
                    ->underline(AnsiColor::Blue)
                    ->addModifier(Modifier::Bold)
                    ->removeModifier(Modifier::Italic)
                    ->removeModifier(Modifier::Underlined);

        $expectedString = sprintf(
            'Style(fg:%s,bg: %s,u:%s,+mod:%d,-mod:%d)',
            '-',
            AnsiColor::Red->debugName(),
            AnsiColor::Blue->debugName(),
            Modifiers::fromModifier(Modifier::Bold)->toInt(),
            Modifiers::fromInt(Modifier::Italic->value | Modifier::Underlined->value)->toInt()
        );

        self::assertEquals($expectedString, (string) $style);
    }
}
