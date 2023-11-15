<?php

declare(strict_types=1);

namespace PhpTui\Term;

interface InformationProvider
{
    /**
     * @template T of TerminalInformation
     * @param class-string<T> $classFqn
     * @return T
     */
    public function for(string $classFqn): ?TerminalInformation;
}
