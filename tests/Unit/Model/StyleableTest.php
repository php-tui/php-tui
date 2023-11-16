<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Widget\Span;
use PHPUnit\Framework\TestCase;

class StyleableTest extends TestCase
{
    /**
     * @dataProvider modifierProvider
     */
    public function testModifiers(int $modifier, string $methodName): void
    {
        /**
         * @var Span $span
         * @phpstan-ignore-next-line
         */
        $span = Span::fromString('Hello')->$methodName();
        self::assertTrue(($span->style->addModifiers & $modifier) === $modifier);

        /**
         * @var Span $span
         * @phpstan-ignore-next-line
         */
        $span = Span::fromString('Hello')->$methodName(false);
        self::assertTrue(($span->style->subModifiers & $modifier) === $modifier);
    }

    /**
     * @dataProvider colorProvider
     */
    public function testFgColors(AnsiColor $color, string $colorName): void
    {
        /**
         * @var Span $span
         * @phpstan-ignore-next-line
         */
        $span = Span::fromString('Hello')->{lcfirst($colorName)}();
        self::assertSame($color, $span->style->fg);
    }

    /**
     * @dataProvider colorProvider
     */
    public function testBgColors(AnsiColor $color, string $colorName): void
    {
        /**
         * @var Span $span
         * @phpstan-ignore-next-line
         */
        $span = Span::fromString('Hello')->{"on{$colorName}"}();
        self::assertSame($color, $span->style->bg);
    }

    /**
     * @return array<array{int-mask-of<Modifier::*>, string}>
     */
    public static function modifierProvider(): array
    {
        return [
            [Modifier::BOLD, 'bold'],
            [Modifier::DIM, 'dim'],
            [Modifier::ITALIC, 'italic'],
            [Modifier::UNDERLINED, 'underlined'],
            [Modifier::SLOWBLINK, 'slowBlink'],
            [Modifier::RAPIDBLINK, 'rapidBlink'],
            [Modifier::REVERSED, 'reversed'],
            [Modifier::HIDDEN, 'hidden'],
            [Modifier::CROSSEDOUT, 'crossedOut'],
        ];
    }

    /**
     * @return array<array{AnsiColor, string}>
     */
    public static function colorProvider(): array
    {
        return array_map(
            fn (AnsiColor $color) => [$color, $color->name],
            array_filter(AnsiColor::cases(), fn (AnsiColor $color) => $color !== AnsiColor::Reset)
        );
    }
}
