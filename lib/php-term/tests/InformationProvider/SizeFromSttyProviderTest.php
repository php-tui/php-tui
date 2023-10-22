<?php

namespace DTL\PhpTerm\Tests\InformationProvider;

use DTL\PhpTerm\InformationProvider\SizeFromSttyProvider;
use DTL\PhpTerm\Size;
use PHPUnit\Framework\TestCase;

class SizeFromSttyProviderTest extends TestCase
{
    public function testSizeFromStty(): void
    {
        $provider = SizeFromSttyProvider::new();
        $size = $provider->for(Size::class);
        self::assertEquals(new Size(1, 2), $size);
    }
}

