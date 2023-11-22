<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\TextEditor;

use OutOfRangeException;
use PhpTui\Tui\Model\Position\Position;
use RuntimeException;

final class TextEditor
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
            mb_substr($line, 0, $this->cursor->x - $length),
            mb_substr($line, $this->cursor->x, strlen($line) - $this->cursor->x),
        );
        $this->setLine($line);
        $this->cursor->x = max(0, $this->cursor->x - 1);
    }

    public function cursorLeft(): void
    {
        $this->cursor = $this->cursor->change(
            fn (int $x, int $y) => [max(0, $x - 1), $y]
        );
    }

    public function cursorRight(): void
    {
        $line = $this->resolveLine();
        $this->cursor = $this->cursor->change(
            fn (int $x, int $y) => [
                min(mb_strlen($line), $x + 1),
                $y
            ]
        );
    }

    public function cursorDown(): void
    {
        $this->cursor = $this->cursor->change(
            fn (int $x, int $y) => [$x, min(count($this->lines) - 1, $y + 1)]
        );
    }
    public function cursorUp(): void
    {
        $this->cursor = $this->cursor->change(
            fn (int $x, int $y) => [$x, max(0, $y - 1)]
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
}
