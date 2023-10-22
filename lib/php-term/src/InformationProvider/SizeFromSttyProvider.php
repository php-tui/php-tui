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
        $out = $this->queryStty();
        if (null === $out) {
            return null;
        }
        return $this->parse($out);

    }

    private function queryStty(): ?string
    {
        $result = $this->runner->run(['stty', '-a']);
        if ($result->exitCode !== 0) {
            return null;
        }

        return $result->stdout;
    }

    private function parse(string $out): ?Size
    {
        if (false === preg_match('{rows ([0-9]+); columns ([0-9]+);}is', $out, $matches)) {
            return null;
        }

        return new Size(intval($matches[1]), intval($matches[2]));
    }
}
