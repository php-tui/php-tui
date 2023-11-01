<?php

namespace PhpTui\BDF;

use Closure;
use RuntimeException;

final class BdfTokenStream
{
    private int $position = 0;

    /**
     * @param array<int,string> $tokens
     */
    public function __construct(private array $tokens)
    {
    }

    public static function fromString(string $string): self
    {
        $tokens = preg_split('{(\s)}', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
        if (false === $tokens) {
            throw new RuntimeException(
                'Could not tokenize string'
            );
        }

        return new self($tokens);
    }

    public function toString(): string
    {
        return implode('', $this->tokens);
    }

    public function is(string $string): bool
    {
        return $this->current() === $string;
    }

    public function current(): ?string
    {
        return $this->tokens[$this->position] ?? null;
    }

    public function advance(): void
    {
        $this->position++;
    }

    /**
     * Parse the line until the line ending and advance past the line ending
     */
    public function parseLine(): string
    {
        $taken = $this->takeWhile(fn (?string $token) => $token !== "\n");
        $this->skipWhitespace();
        return $taken;
    }

    /**
     * @param Closure(string):bool $closure
     */
    private function takeWhile(Closure $closure): string
    {
        $taken = '';
        $current = $this->current();
        if (null === $current) {
            return $taken;
        }
        while ($closure($current)) {
            $taken .= $this->current();
            $this->advance();
            $current = $this->current();
            if ($current === null) {
                break;
            }
        }

        return $taken;
    }

    private function skipWhitespace(): void
    {
        $this->takeWhile(fn (string $token) => trim($token) === '');
    }

    public function parseInt(): ?int
    {
        $this->skipWhitespace();
        if (is_numeric($this->current())) {
            $int = (int)$this->current();
            $this->advance();
            $this->skipWhitespace();
            return (int)$int;
        }

        return null;
    }
}
