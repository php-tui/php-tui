<?php

namespace PhpTui\Tui\Tests\Model\Style;

use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Style;
use PHPUnit\Framework\TestCase;

class ModifierStylerTest extends TestCase
{
    /**
     * @dataProvider modifierProvider
     */
    public function testModifiers(int $modifier, string $methodName): void
    {
        /** @phpstan-ignore-next-line */
        $style = Style::default()->$methodName();
        self::assertTrue(($style->addModifiers & $modifier) === $modifier);

        /** @phpstan-ignore-next-line */
        $style->$methodName(false);
        self::assertTrue(($style->subModifiers & $modifier) === $modifier);
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
}
