<?php

namespace DTL\PhpTerm\Tests\InformationProvider;

use DTL\PhpTerm\InformationProvider\SizeFromEnvVarProvider;
use DTL\PhpTerm\Size;
use PHPUnit\Framework\TestCase;

class SizeFromEnvVarProviderTest extends TestCase
{
    public function testProvider(): void
    {
        [$lines, $cols] = [getenv('LINES'), getenv('COLUMNS')];
        putenv('LINES=');
        putenv('COLUMNS=');

        $size = (new SizeFromEnvVarProvider())->for(Size::class);
        self::assertNull($size);
        putenv('LINES=1');
        putenv('COLUMNS=2');

        $size = (new SizeFromEnvVarProvider())->for(Size::class);
        self::assertEquals(new Size(1, 2), $size);

    }
}
