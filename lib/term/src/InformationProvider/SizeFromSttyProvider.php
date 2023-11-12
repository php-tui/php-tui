<?php

namespace PhpTui\Term\InformationProvider;

use PhpTui\Term\InformationProvider;
use PhpTui\Term\ProcessRunner;
use PhpTui\Term\ProcessRunner\ProcRunner;
use PhpTui\Term\Size;
use PhpTui\Term\TerminalInformation;

final class SizeFromSttyProvider implements InformationProvider
{
    private function __construct(private ProcessRunner $runner)
    {
    }

    public static function new(?ProcessRunner $processRunner = null): self
    {
        return new self($processRunner ?? new ProcRunner());
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
        /**
         * @phpstan-ignore-next-line */
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
