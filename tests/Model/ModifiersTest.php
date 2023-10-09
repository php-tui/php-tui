<?php

namespace DTL\PhpTui\Tests\Model;

use DTL\PhpTui\Model\Modifier;
use DTL\PhpTui\Model\Modifiers;
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
    public function testRemove(): void
    {
        self::assertEquals(
            Modifier::None->value,
            Modifiers::fromModifier(Modifier::Italic)->sub(Modifier::Italic)->toInt()
        );
        self::assertEquals(
            Modifier::Bold->value,
            Modifiers::fromModifier(Modifier::Italic)->add(Modifier::Bold)->sub(Modifier::Italic)->toInt()
        );
    }
}
