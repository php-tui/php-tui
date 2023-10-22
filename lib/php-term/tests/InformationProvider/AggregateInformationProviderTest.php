<?php

namespace DTL\PhpTerm\Tests\InformationProvider;

use DTL\PhpTerm\InformationProvider\AggregateInformationProvider;
use DTL\PhpTerm\TerminalInformation;
use PHPUnit\Framework\TestCase;

class AggregateInformationProviderTest extends TestCase
{
    public function testReturnsFirstNonNullResult(): void
    {
        $info = new TestInfo();
        $provider = AggregateInformationProvider::new([
            ClosureInformationProvider::new(function (string $classFqn) {
                return null;
            }),
            ClosureInformationProvider::new(function (string $classFqn) use ($info) {
                if ($classFqn !== TestInfo::class) {
                    return null;
                }
                return $info;
            })
        ]);
        self::assertSame($info, $provider->for(TestInfo::class));
    }

    public function testNullWithNoProviders(): void
    {
        $info = new TestInfo();
        $provider = AggregateInformationProvider::new([]);
        self::assertNull($provider->for(TestInfo::class));
    }
}

class TestInfo implements TerminalInformation {
}
