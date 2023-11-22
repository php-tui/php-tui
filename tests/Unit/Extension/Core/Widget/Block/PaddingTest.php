<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget\Block;

use PhpTui\Tui\Extension\Core\Widget\Block\Padding;
use PHPUnit\Framework\TestCase;

class PaddingTest extends TestCase
{
    public function testAll(): void
    {
        $padding = Padding::all(2);
        self::assertEquals(2, $padding->top);
        self::assertEquals(2, $padding->bottom);
        self::assertEquals(2, $padding->left);
        self::assertEquals(2, $padding->right);
    }

    public function testHorizontal(): void
    {
        $padding = Padding::horizontal(2);
        self::assertEquals(0, $padding->top);
        self::assertEquals(0, $padding->bottom);
        self::assertEquals(2, $padding->left);
        self::assertEquals(2, $padding->right);
    }

    public function testVertical(): void
    {
        $padding = Padding::vertical(2);
        self::assertEquals(2, $padding->top);
        self::assertEquals(2, $padding->bottom);
        self::assertEquals(0, $padding->left);
        self::assertEquals(0, $padding->right);
    }

    public function testNone(): void
    {
        $padding = Padding::none();
        self::assertEquals(0, $padding->top);
        self::assertEquals(0, $padding->bottom);
        self::assertEquals(0, $padding->left);
        self::assertEquals(0, $padding->right);
    }

    public function testFromScalars(): void
    {
        $padding = Padding::fromScalars(1, 2, 3, 4);
        self::assertEquals(1, $padding->left);
        self::assertEquals(2, $padding->right);
        self::assertEquals(3, $padding->top);
        self::assertEquals(4, $padding->bottom);
    }

    public function testLeft(): void
    {
        $padding = Padding::none()->left(2);
        self::assertEquals(0, $padding->top);
        self::assertEquals(0, $padding->bottom);
        self::assertEquals(2, $padding->left);
        self::assertEquals(0, $padding->right);
    }

    public function testRight(): void
    {
        $padding = Padding::none()->right(2);
        self::assertEquals(0, $padding->top);
        self::assertEquals(0, $padding->bottom);
        self::assertEquals(0, $padding->left);
        self::assertEquals(2, $padding->right);
    }

    public function testTop(): void
    {
        $padding = Padding::none()->top(2);
        self::assertEquals(2, $padding->top);
        self::assertEquals(0, $padding->bottom);
        self::assertEquals(0, $padding->left);
        self::assertEquals(0, $padding->right);
    }

    public function testBottom(): void
    {
        $padding = Padding::none()->bottom(2);
        self::assertEquals(0, $padding->top);
        self::assertEquals(2, $padding->bottom);
        self::assertEquals(0, $padding->left);
        self::assertEquals(0, $padding->right);
    }

    public function testMixed(): void
    {
        $padding = Padding::all(4)->left(2);
        self::assertEquals(4, $padding->top);
        self::assertEquals(4, $padding->bottom);
        self::assertEquals(2, $padding->left);
        self::assertEquals(4, $padding->right);
    }
}
