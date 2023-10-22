<?php

namespace DTL\PhpTerm;

interface ProcessRunner
{
    /**
     * @param string[] $command
     */
    public function run(array $command): ProcessResult;
}
