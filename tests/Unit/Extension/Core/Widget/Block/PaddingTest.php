<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget\Block;

use PhpTui\Tui\Extension\Core\Widget\Block\Padding;
use PHPUnit\Framework\TestCase;

final class PaddingTest extends TestCase
{
    public function testAll(): void
    {
        $padding = Padding::all(2);
        self::assertEquals(2, $padding->left);
        self::assertEquals(2, $padding->right);
        self::assertEquals(2, $padding->top);
        self::assertEquals(2, $padding->bottom);
    }

    public function testHorizontal(): void
    {
        $padding = Padding::horizontal(2);
        self::assertEquals(2, $padding->left);
        self::assertEquals(2, $padding->right);
        self::assertEquals(0, $padding->top);
        self::assertEquals(0, $padding->bottom);
    }

    public function testVertical(): void
    {
        $padding = Padding::vertical(2);
        self::assertEquals(0, $padding->left);
        self::assertEquals(0, $padding->right);
        self::assertEquals(2, $padding->top);
        self::assertEquals(2, $padding->bottom);
    }

    public function testNone(): void
    {
        $padding = Padding::none();
        self::assertEquals(0, $padding->left);
        self::assertEquals(0, $padding->right);
        self::assertEquals(0, $padding->top);
        self::assertEquals(0, $padding->bottom);
    }

    public function testFromScalars(): void
    {
        $padding = Padding::fromScalars(1, 2, 3, 4);
        self::assertEquals(3, $padding->top);
        self::assertEquals(4, $padding->bottom);
        self::assertEquals(1, $padding->left);
        self::assertEquals(2, $padding->right);
    }

    public function testLeft(): void
    {
        $padding = Padding::left(2);
        self::assertEquals(2, $padding->left);
        self::assertEquals(0, $padding->right);
        self::assertEquals(0, $padding->top);
        self::assertEquals(0, $padding->bottom);
    }

    public function testRight(): void
    {
        $padding = Padding::right(2);
        self::assertEquals(0, $padding->left);
        self::assertEquals(2, $padding->right);
        self::assertEquals(0, $padding->top);
        self::assertEquals(0, $padding->bottom);
    }

    public function testTop(): void
    {
        $padding = Padding::top(2);
        self::assertEquals(0, $padding->left);
        self::assertEquals(0, $padding->right);
        self::assertEquals(2, $padding->top);
        self::assertEquals(0, $padding->bottom);
    }

    public function testBottom(): void
    {
        $padding = Padding::bottom(2);
        self::assertEquals(0, $padding->left);
        self::assertEquals(0, $padding->right);
        self::assertEquals(0, $padding->top);
        self::assertEquals(2, $padding->bottom);
    }

    public function testMixed(): void
    {
        $padding = Padding::fromScalars(left: 2, top: 4);
        self::assertEquals(2, $padding->left);
        self::assertEquals(0, $padding->right);
        self::assertEquals(4, $padding->top);
        self::assertEquals(0, $padding->bottom);
    }
}
