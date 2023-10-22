<?php

namespace DTL\PhpTerm\InformationProvider;

use DTL\PhpTerm\InformationProvider;
use DTL\PhpTerm\ProcessRunner;
use DTL\PhpTerm\ProcessRunner\ProcRunner;
use DTL\PhpTerm\Size;
use DTL\PhpTerm\TerminalInformation;

final class SizeFromSttyProvider implements InformationProvider
{
    private function __construct(private ProcessRunner $runner)
    {
    }

    public static function new(?ProcessRunner $processRunner = null): self
    {
        return new self($processRunner ?: new ProcRunner());
    }

    public function for(string $classFqn): ?TerminalInformation
    {
        if ($classFqn !== Size::class) {
            return null;
        }
        return null;

    }

    private function queryStty(): string
    {
        $out = $this->runner->run(['stty', '-a']);
    }
}
