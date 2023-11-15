<?php

declare(strict_types=1);

namespace PhpTui\Term;

interface ProcessRunner
{
    /**
     * @param string[] $command
     */
    public function run(array $command): ProcessResult;
}
