<?php

namespace DTL\Cassowary\Tests;

use DTL\Cassowary\Row;
use DTL\Cassowary\Symbol;
use PHPUnit\Framework\TestCase;

class RowTest extends TestCase
{
    public function testInsertSymbol(): void
    {
        $row = Row::new(10.0);
        $row->insertSymbol(Symbol::invalid(), 1.0);
        self::assertCount(1, $row);
    }
}
