<?php

namespace PhpTui\Tui\Tests\Model;

use PhpTui\Tui\Model\Modifier;
use PhpTui\Tui\Model\Modifiers;
use PHPUnit\Framework\TestCase;

class ModifiersTest extends TestCase
{
    public function testAdd(): void
    {
        self::assertEquals(
            Modifier::None->value,
            Modifiers::none()->add(Modifier::None)->toInt()
        );
        self::assertEquals(
            Modifier::Bold->value,
            Modifiers::none()->add(Modifier::Bold)->toInt()
        );
        self::assertEquals(
            0b000000000101,
            Modifiers::none()->add(Modifier::Bold)->add(Modifier::Italic)->toInt()
        );
    }
    public function testSub(): void
    {
        // subtract modifier
        self::assertEquals(
            Modifier::None->value,
            Modifiers::fromModifier(Modifier::Italic)->sub(Modifier::Italic)->toInt()
        );
        self::assertEquals(
            Modifier::Bold->value,
            Modifiers::fromModifier(Modifier::Italic)->add(Modifier::Bold)->sub(Modifier::Italic)->toInt()
        );

        // subtracts modifiers
        self::assertEquals(
            Modifier::Bold->value,
            Modifiers::fromModifier(Modifier::Italic)
                ->add(Modifier::Bold)
                ->sub(Modifiers::fromModifier(Modifier::Italic))
                ->toInt()
        );
    }

    public function testRemove(): void
    {
        self::assertEquals(
            '100',
            Modifiers::fromModifier(Modifier::Italic)->toBin(),
        );
        self::assertEquals(
            '0',
            Modifiers::fromModifier(Modifier::Italic)->remove(Modifiers::fromModifier(Modifier::Italic))->toBin(),
        );
    }

    public function testInsert(): void
    {
        self::assertEquals(
            '0',
            Modifiers::none()->toBin(),
        );
        self::assertEquals(
            '101',
            Modifiers::fromModifier(Modifier::Italic)->insert(Modifiers::fromModifier(Modifier::Bold))->toBin()
        );
    }

    public function testContains(): void
    {
        self::assertTrue(Modifiers::fromModifier(Modifier::Italic)->contains(Modifier::Italic));
        self::assertTrue(Modifiers::fromModifier(Modifier::Italic)->add(Modifier::Bold)->contains(Modifier::Italic));
        self::assertFalse(Modifiers::fromModifier(Modifier::Italic)->add(Modifier::Bold)->contains(Modifier::Reversed));

    }
}
