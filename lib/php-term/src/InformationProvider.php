<?php

namespace DTL\PhpTerm;

interface InformationProvider
{
    /**
     * @template T of TerminalInformation
     * @param class-string<T> $classFqn
     * @return T
     */
    public function for(string $classFqn): ?TerminalInformation;
}
