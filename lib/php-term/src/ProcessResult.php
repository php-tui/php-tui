<?php

namespace DTL\PhpTerm;

final class ProcessResult
{
    public function __construct(public int $exitCode, public $stdout)
    {
    }

}
