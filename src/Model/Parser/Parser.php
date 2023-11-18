<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Parser;

interface Parser
{
    public function parse(string $input): array;
}
