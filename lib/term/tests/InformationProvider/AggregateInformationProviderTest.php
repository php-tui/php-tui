<?php

declare(strict_types=1);

namespace PhpTui\Term\Tests\InformationProvider;

use PhpTui\Term\InformationProvider\AggregateInformationProvider;
use PhpTui\Term\InformationProvider\ClosureInformationProvider;
use PhpTui\Term\TerminalInformation;
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

class TestInfo implements TerminalInformation
{
}
