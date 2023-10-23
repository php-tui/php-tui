<?php

namespace PhpTui\Term;

final class ProcessResult
{
    public function __construct(public int $exitCode, public $stdout)
    {
    }

}
