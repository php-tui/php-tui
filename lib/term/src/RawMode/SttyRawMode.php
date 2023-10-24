<?php

namespace PhpTui\Term\RawMode;

use PhpTui\Term\ProcessRunner;
use PhpTui\Term\ProcessRunner\ProcRunner;
use PhpTui\Term\RawMode;
use RuntimeException;

class SttyRawMode implements RawMode
{
    private ?string $originalSettings;

    private function __construct(private ProcessRunner $runner)
    {
    }

    public static function new(?ProcessRunner $processRunner = null): self
    {
        return new self($processRunner ?: new ProcRunner());
    }

    public function enable(): void
    {
        $result = $this->runner->run(['stty', '-g']);
        if ($result->exitCode !== 0) {
            throw new RuntimeException(
                'Could not get stty settings'
            );
        }
        $this->originalSettings = trim($result->stdout);
        $result = $this->runner->run(['stty', 'raw']);
        if ($result->exitCode !== 0) {
            throw new RuntimeException(
                'Could not set raw mode'
            );
        }
    }

    public function disable(): void
    {
        if (null === $this->originalSettings) {
            return;
        }
        $result = $this->runner->run(['stty', $this->originalSettings]);
        if ($result->exitCode !== 0) {
            throw new RuntimeException(sprintf(
                'Could not restore from raw mode: %s', $result->stderr
            ));
        }
    }
}
