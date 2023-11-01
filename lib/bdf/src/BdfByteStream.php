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
        while (null !== $char = $stream->shift()) {
            if (false === $closure($char)) {
                $stream->unshift($char);
                break;
            }
        }
        return BdfResult::ok(
            null,
            $stream
        );
    }

    /**
     * @return BdfResult<string>
     */
    public function takeExact(string $string): BdfResult
    {
        $match = str_split($string);
        $stream = $this->clone();
        $i = 0;
        while (null !== $byte = $stream->shift()) {
            if (!isset($match[$i])) {
                break;
            }
            if ($match[$i] !== $byte) {
                return BdfResult::failure('', $this);
            }
            $i++;
        }
        return BdfResult::ok($string, $stream);
    }

    /**
     * @param Closure(string): bool $closure
     * @return BdfResult<string>
     */
    public function takeWhile(Closure $closure): BdfResult
    {
        $stream = $this->clone();
        $matches = '';
        while (null !== $byte = $stream->shift()) {
            if ($closure($byte)) {
                $matches .= $byte;
                continue;
            }
            $stream->unshift($byte);
            return BdfResult::ok($matches, $stream);
        }
        return BdfResult::failure('', $this);
    }

    private function clone(): self
    {
        return clone $this;
    }

    private function shift(): ?string
    {
        return array_shift($this->bytes);
    }

    private function unshift(string $string): void
    {
        if (strlen($string) === 1) {
            array_unshift($this->bytes, $string);
            return;
        }
        foreach (array_reverse(str_split($string)) as $char) {
            array_unshift($this->bytes, $char);
        }
    }

    /**
     * @template T
     * @param callable(BdfByteStream): BdfResult<mixed> $left
     * @param callable(BdfByteStream): BdfResult<T> $value
     * @param callable(BdfByteStream): BdfResult<mixed> $right
     * @return Closure(BdfResult<T>)
     */
    public function delimited(callable $left, callable $value, callable $right): Closure
    {
        return function (BdfByteStream $stream) use ($left, $value, $right) {
            $left = $left($stream);
            $fail = fn () => BdfResult::failure(null, $stream);
            if (false === $left->isOk()) {
                return $fail();
            }
            $result = $value($left->rest);
            $right = $right($result->rest);
            if (false === $right->isOk()) {
                return $fail();
            }

            return $result;
        };

    }

    /**
     * @return BdfResult<string>
     */
    public function takeUntil(string $target): BdfResult
    {
        $targetLen = strlen($target);
        $stream = $this->clone();
        $string = '';
        while (null !== $byte = $stream->shift()) {
            $string .= $byte;
            if (strlen($string) < $targetLen) {
                continue;
            }
            if (substr($string, -$targetLen) === $target) {
                $stream->unshift($target);
                return BdfResult::ok(substr($string, 0, -$targetLen), $stream);
            }
        }

        return BdfResult::failure('', $this);
    }

    public function count(): int
    {
        return count($this->bytes);
    }

    public function toString(): string
    {
        return implode('', $this->bytes);
    }
}
