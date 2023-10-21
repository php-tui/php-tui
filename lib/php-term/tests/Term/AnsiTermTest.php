<?php

namespace DTL\PhpTerm\Tests\Term;

use DTL\PhpTerm\TermColor;
use DTL\PhpTerm\TermCmd;
use DTL\PhpTerm\TermCommand;
use DTL\PhpTerm\Term\AnsiTerm;
use DTL\PhpTerm\Writer\BufferWriter;
use PHPUnit\Framework\TestCase;

class AnsiTermTest extends TestCase
{
    public function testControlSequences(): void
    {
        $this->assertAnsiCode('10', TermCmd::setBackgroundColor(TermColor::Green));
    }

    private function assertAnsiCode(string $string, TermCommand $command): void
    {
        $writer = BufferWriter::new();
        $term = AnsiTerm::new($writer);
        $term->draw([$command]);
        self::assertEquals(sprintf('\e[%s', $string), $writer->toString());
    }
}
