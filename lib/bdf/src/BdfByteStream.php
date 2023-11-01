<?php

namespace PhpTui\BDF;

use Closure;

final class BdfByteStream
{
    /**
     * @param array<int,string> $bytes
     */
    public function __construct(private array $bytes)
    {
    }

    public static function fromString(string $string): self
    {
        return new self(str_split($string));
    }
    /**
     * @return void
     */
    public function match(string $string)
    {
    }

    /**
     * @param Closure(string): bool $closure
     * @return BdfResult<null>
     */
    public function skipWhile(Closure $closure): BdfResult
    {
        $stream = $this->clone();
        while ($char = $stream->shift()) {
            if (false === $closure($char)) {
                $stream->unshift($char);
                break;
            }
        }
        return new BdfResult(
            null,
            $stream
        );
    }

    /**
     * @return ?BdfResult<string>
     */
    public function takeExact(string $string): ?BdfResult
    {
        $match = str_split($string);
        $stream = $this->clone();
        $i = 0;
        while ($byte = $stream->shift()) {
            if (!isset($match[$i])) {
                break;
            }
            if ($match[$i] !== $byte) {
                return null;
            }
            $i++;
        }
        return new BdfResult($string, $stream);
    }

    /**
     * @param Closure(string): bool $closure
     * @return BdfResult<string>
     */
    public function takeWhile(Closure $closure): ?BdfResult
    {
        $stream = $this->clone();
        $matches = [];
        while ($byte = $stream->shift()) {
            if ($closure($byte)) {
                $matches[] = $byte;
                continue;
            }
            $stream->unshift($byte);
            return new BdfResult(implode('', $matches), $stream);
        }
        return null;
    }

    private function clone(): self
    {
        return clone $this;
    }

    private function shift(): ?string
    {
        return array_shift($this->bytes);
    }

    private function unshift(string $char): void
    {
        array_unshift($this->bytes, $char);
    }
}
