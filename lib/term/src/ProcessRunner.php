<?php

namespace PhpTui\Term;

interface ProcessRunner
{
    /**
     * @param string[] $command
     */
    public function run(array $command): ProcessResult;
}
