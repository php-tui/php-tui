<?php

namespace DTL\PhpTerm\Tests\InformationProvider;

use Closure;
use DTL\PhpTerm\InformationProvider;
use DTL\PhpTerm\TerminalInformation;

/**
 * This is here to facilitate testing.
 */
final class ClosureInformationProvider implements InformationProvider
{
    /**
     * @template T of TerminalInformation
     * @param Closure(class-string<T>): (T|null) $closure
     */
    private function __construct(private Closure $closure)
    {
    }

    public function for(string $classFqn): ?TerminalInformation
    {
        return ($this->closure)($classFqn);
    }

    /**
     * @template T of TerminalInformation
     * @param Closure(class-string<T>): (T|null) $closure
     */
    public static function new(Closure $closure): self
    {
        return new self($closure);
    }
}

