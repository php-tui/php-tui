<?php

namespace PhpTui\Term\ProcessRunner;

use Closure;
use PhpTui\Term\ProcessResult;
use PhpTui\Term\ProcessRunner;

/**
 * Implementation to be used for test scenarios.
 */
class ClosureRunner  implements ProcessRunner
{
    /**
     * @param Closure(string[]): ProcessResult $closure
     */
    public function __construct(private Closure $closure)
    {
    }

    public function run(array $command): ProcessResult
    {
        return ($this->closure)($command);
    }

    /**
     * @param Closure(string[]): ProcessResult $closure
     */
    public static function new(Closure $closure): self
    {
        return new self($closure);
    }
}
