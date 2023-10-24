<?php

namespace PhpTui\Term\Reader;

use PhpTui\Term\Reader;

/**
 * For use in test scenarios
 */
final class InMemoryReader implements Reader
{
    /**
     * @param string[] $chunks
     */
    public function __construct(private array $chunks)
    {
    }

    public function read(): ?string
    {
        while (null !== $chunk = array_shift($this->chunks)) {
            return $chunk;
        }

        return null;
    }

}
