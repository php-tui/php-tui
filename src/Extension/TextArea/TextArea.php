<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\TextArea;

use OutOfRangeException;
use PhpTui\Tui\Model\Position\Position;
use RuntimeException;

final class TextArea
{
    /**
     * @param list<string> $lines
     */
    private function __construct(
        private Position $cursor,
        private array  $lines,
    ) {
    }

    public static function fromString(string $contents): self
    {
        return new self(Position::at(0, 0), explode("\n", $contents));
    }

    public function toString(): string
    {
        return implode("\n", $this->lines);
    }

    /**
     * Return the text editors cursor position within relative to the text
     * document.
     */
    public function cursorPosition(): Position
    {
        return $this->cursor;
    }

    /**
     * Insert text at the given offset. If length is provided it will replace
     * that number of multibyte characters.
     *
     * @param int<0,max> $length
     */
    public function insert(string $text, int $length = 0): void
    {
        /** @phpstan-ignore-next-line */
        if ($length < 0) {
            throw new OutOfRangeException(sprintf(
                'Insert length must be > 0, got %d',
                $length
            ));
        }

        $line = $this->resolveLine();
        $line = sprintf(
            '%s%s%s',
            mb_substr($line, 0, $this->cursor->x),
            $text,
            mb_substr($line, $this->cursor->x + $length, strlen($line) - $this->cursor->x),
        );
        $this->cursor->x += mb_strlen($text);
        $this->setLine($line);
    }

    public function delete(int $length = 1): void
    {
        $line = $this->resolveLine();
        $line = sprintf(
            '%s%s',
            mb_substr($line, 0, max(0, $this->cursor->x - $length)),
            mb_substr($line, $this->cursor->x, max(0, strlen($line) - $this->cursor->x)),
        );
        $this->setLine($line);
        $this->cursor->x = max(0, $this->cursor->x - 1);
    }

    public function cursorLeft(int $amount = 1): void
    {
        $this->cursor = $this->cursor->change(
            static fn (int $x, int $y): array => [max(0, $x - $amount), $y]
        );
    }

    public function cursorRight(int $amount = 1): void
    {
        $line = $this->resolveLine();
        $this->cursor = $this->cursor->change(
            static fn (int $x, int $y): array => [
                min(mb_strlen($line), $x + $amount),
                $y
            ]
        );
    }

    public function cursorDown(int $amount = 1): void
    {
        $this->cursor = $this->cursor->change(
            fn (int $x, int $y): array => [$x, min(count($this->lines) - 1, $y + $amount)]
        );
    }
    public function cursorUp(int $amount = 1): void
    {
        $this->cursor = $this->cursor->change(
            static fn (int $x, int $y): array => [$x, max(0, $y - $amount)]
        );
    }

    /**
     * @param string[] $lines
     */
    public static function fromLines(array $lines): self
    {
        return new self(Position::at(0, 0), $lines);
    }

    private function resolveLine(): string
    {
        $position = $this->cursor;

        // if the line at the cursor doesn't exist
        if (!isset($this->lines[$position->y])) {
            throw new RuntimeException(sprintf(
                'There is no line at position: %d',
                $position->y
            ));
        }

        // return the current line
        return $this->lines[$position->y];
    }

    private function setLine(string $line): void
    {
        $position = $this->cursor;
        $this->lines[$position->y] = $line;
    }

    public function newLine(): void
    {
        $line = $this->resolveLine();
        $pre = mb_substr($line, 0, $this->cursor->x);
        $this->lines = array_values([
            ...array_slice($this->lines, 0, $this->cursor->y),
            $pre,
            ...array_slice($this->lines, $this->cursor->y)
        ]);
        $post = mb_substr($line, $this->cursor->x);
        $this->cursorDown();
        $this->setLine($post);
        $this->cursor->x = 0;
    }

    public function lineCount(): int
    {
        return count($this->lines);
    }

    /**
     * @return string[]
     */
    public function viewportLines(int $offset, int $height): array
    {
        return array_slice($this->lines, $offset, $height);
    }
}
