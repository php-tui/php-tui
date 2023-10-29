<?php

namespace PhpTui\Term\Tests\Painter;

use PhpTui\Term\ClearType;
use PhpTui\Term\Colors;
use PhpTui\Term\Actions;
use PhpTui\Term\Action;
use PhpTui\Term\Painter\AnsiPainter;
use PhpTui\Term\Writer\BufferWriter;
use PHPUnit\Framework\TestCase;

class AnsiPainterTest extends TestCase
{
    public function testControlSequences(): void
    {
        $this->assertAnsiCode('48;5;2m', Actions::setBackgroundColor(Colors::Green));
        $this->assertAnsiCode('38;5;4m', Actions::setForegroundColor(Colors::Blue));
        $this->assertAnsiCode('48;2;2;3;4m', Actions::setRgbBackgroundColor(2, 3, 4));
        $this->assertAnsiCode('38;2;2;3;4m', Actions::setRgbForegroundColor(2, 3, 4));
        $this->assertAnsiCode('?25l', Actions::cursorHide());
        $this->assertAnsiCode('?25h', Actions::cursorShow());
        $this->assertAnsiCode('?1049h', Actions::alternateScreenEnable());
        $this->assertAnsiCode('?1049l', Actions::alternateScreenDisable());
        $this->assertAnsiCode('2;3H', Actions::moveCursor(2, 3));
        $this->assertAnsiCode('0m', Actions::reset());
        $this->assertAnsiCode('1m', Actions::bold(true));
        $this->assertAnsiCode('2m', Actions::dim(true));
        $this->assertAnsiCode('3m', Actions::italic(true));
        $this->assertAnsiCode('23m', Actions::italic(false));
        $this->assertAnsiCode('4m', Actions::underline(true));
        $this->assertAnsiCode('24m', Actions::underline(false));
        $this->assertAnsiCode('5m', Actions::slowBlink(true));
        $this->assertAnsiCode('7m', Actions::reverse(true));
        $this->assertAnsiCode('8m', Actions::hidden(true));
        $this->assertAnsiCode('9m', Actions::strike(true));
        $this->assertAnsiCode('2J', Actions::clear(ClearType::All));
    }

    private function assertAnsiCode(string $string, Action $command): void
    {
        $writer = BufferWriter::new();
        $term = AnsiPainter::new($writer);
        $term->paint([$command]);
        self::assertEquals(json_encode(sprintf("\033[%s", $string)), json_encode($writer->toString()), $command::class);
    }
}
