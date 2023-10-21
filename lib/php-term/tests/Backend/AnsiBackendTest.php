<?php

namespace DTL\PhpTerm\Tests\Backend;

use DTL\PhpTerm\TermColor;
use DTL\PhpTerm\TermCmd;
use DTL\PhpTerm\TermCommand;
use DTL\PhpTerm\Backend\AnsiBackend;
use DTL\PhpTerm\Writer\BufferWriter;
use PHPUnit\Framework\TestCase;

class AnsiBackendTest extends TestCase
{
    public function testControlSequences(): void
    {
        $this->assertAnsiCode('48;5;2m', TermCmd::setBackgroundColor(TermColor::Green));
        $this->assertAnsiCode('38;5;4m', TermCmd::setForegroundColor(TermColor::Blue));
        $this->assertAnsiCode('48;2;2;3;4m', TermCmd::setRgbBackgroundColor(2, 3, 4));
        $this->assertAnsiCode('38;2;2;3;4m', TermCmd::setRgbForegroundColor(2, 3, 4));
        $this->assertAnsiCode('?25l', TermCmd::cursorHide());
        $this->assertAnsiCode('?25h', TermCmd::cursorShow());
        $this->assertAnsiCode('?1049h', TermCmd::alternateScreenEnable());
        $this->assertAnsiCode('?1049l', TermCmd::alternateScreenDisable());
        $this->assertAnsiCode('2;3H', TermCmd::moveCursor(2, 3));
        $this->assertAnsiCode('0m', TermCmd::reset());
        $this->assertAnsiCode('1m', TermCmd::bold(true));
        $this->assertAnsiCode('2m', TermCmd::dim(true));
        $this->assertAnsiCode('3m', TermCmd::italic(true));
        $this->assertAnsiCode('23m', TermCmd::italic(false));
        $this->assertAnsiCode('4m', TermCmd::underline(true));
        $this->assertAnsiCode('24m', TermCmd::underline(false));
        $this->assertAnsiCode('5m', TermCmd::blink(true));
        $this->assertAnsiCode('7m', TermCmd::reverse(true));
        $this->assertAnsiCode('8m', TermCmd::hidden(true));
        $this->assertAnsiCode('9m', TermCmd::strike(true));
    }

    private function assertAnsiCode(string $string, TermCommand $command): void
    {
        $writer = BufferWriter::new();
        $term = AnsiBackend::new($writer);
        $term->draw([$command]);
        self::assertEquals(json_encode(sprintf("\033[%s", $string)), json_encode($writer->toString()), $command::class);
    }
}
