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

    public function is(BdfToken $token): bool
    {
        return $this->current() === $token->name;
    }

    public function isNot(BdfToken $token): bool
    {
        return !$this->is($token);
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
        $taken = $this->takeWhile(
            static fn (string $token): bool => $token !== "\n"
        );
        $this->skipWhitespace();
        $this->skipComments();
        return $taken;
    }

    public function skipWhitespace(): void
    {
        $this->skipWhile(
            static fn (string $token): bool => trim($token) === ''
        );
    }

    public function parseInt(): ?int
    {
        $this->skipWhitespace();
        if (is_numeric($this->current())) {
            $int = (int)$this->current();
            $this->advance();
            $this->skipWhitespace();
            $this->skipComments();
            return $int;
        }

        return null;
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
            $taken .= $current;
            $this->advance();
            $current = $this->current();
            if ($current === null) {
                break;
            }
        }

        return $taken;
    }

    /**
     * @param Closure(string):bool $closure
     */
    private function skipWhile(Closure $closure): void
    {
        $current = $this->current();
        while ($current === null || $closure($current)) {
            $this->advance();
            $current = $this->current();
        }
    }

    private function skipComments(): void
    {
        while ($this->current() === 'COMMENT') {
            $this->parseLine();
        }
    }
}
