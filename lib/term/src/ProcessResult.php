<?php

declare(strict_types=1);

namespace PhpTui\Term;

final class ProcessResult
{
    public function __construct(public int $exitCode, public string $stdout, public string $stderr)
    {
    }

}
