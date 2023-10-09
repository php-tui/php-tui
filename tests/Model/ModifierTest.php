<?php

namespace DTL\PhpTui\Tests\Model;

use DTL\PhpTui\Model\Modifier;
use PHPUnit\Framework\TestCase;

class ModifierTest extends TestCase
{
    public function testAdd(): void
    {
        self::assertEquals(Modifier::None, Modifier::None->add(Modifier::None));
        self::assertEquals(Modifier::Bold, Modifier::None->add(Modifier::Bold));
        self::assertEquals(0b000000000101, Modifier::None->add(Modifier::Bold)->add(Modifier::Italic)->value);
    }
}
