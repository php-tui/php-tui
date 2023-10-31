<?php

namespace PhpTui\BDF;

use Closure;

final class BdfByteStream
{
    public function __construct(private array $bytes)
    {
    }

    public static function fromString(string $string): self
    {
        return new self(str_split($string));
    }

    public function match(string $string)
    {
    }

    /**
     * @param Closure(string): bool $closure
     */
    public function skipWhile(Closure $closure): void
    {
        while ($this->bytes) {
            $char = array_shift($this->bytes);
            if (false === $closure($char)) {
                array_unshift($this->bytes, $char);
                return;
            }
        }
    }

    public function takeExact(string $string): ?string
    {
        $match = str_split($string);
        $original = $this->bytes;
        $i = 0;
        while ($this->bytes) {
            if (!isset($match[$i])) {
                break;
            }
            $byte = array_shift($this->bytes);
            if ($match[$i] !== $byte) {
                // reset
                $this->bytes = $original;
                return null;
            }
            $i++;
        }
        return $string;
    }

    /**
     * @param Closure(string): bool $closure
     */
    public function takeWhile(Closure $closure): ?string
    {
        $matches = [];
        while ($this->bytes) {
            $byte = array_shift($this->bytes);
            if ($closure($byte)) {
                $matches[] = $byte;
                continue;
            }
            array_unshift($this->bytes, $byte);
            return implode('', $matches);
        }
        return null;
    }
}
