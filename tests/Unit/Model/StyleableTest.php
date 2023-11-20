<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Model;

use PhpTui\Tui\Model\Color\AnsiColor;
use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Text\Span;
use PHPUnit\Framework\TestCase;

class StyleableTest extends TestCase
{
    public function testFgBg(): void
    {
        $span = Span::fromString('Hello')->fg(AnsiColor::Blue)->bg(AnsiColor::Red);

        self::assertSame(AnsiColor::Blue, $span->style->fg);
        self::assertSame(AnsiColor::Red, $span->style->bg);
    }

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
        $methodName = lcfirst($colorName);

        /**
         * @var Span $span
         * @phpstan-ignore-next-line
         */
        $span = Span::fromString('Hello')->$methodName();
        self::assertSame($color, $span->style->fg);
    }

    /**
     * @dataProvider colorProvider
     */
    public function testBgColors(AnsiColor $color, string $colorName): void
    {
        $methodName = sprintf('on%s', $colorName);

        /**
         * @var Span $span
         * @phpstan-ignore-next-line
         */
        $span = Span::fromString('Hello')->$methodName();
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
            static fn (AnsiColor $color): array => [$color, $color->name],
            array_filter(AnsiColor::cases(), static fn (AnsiColor $color): bool => $color !== AnsiColor::Reset)
        );
    }
}
