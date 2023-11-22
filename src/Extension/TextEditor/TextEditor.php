<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\TextEditor;

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

    public function cursorPosition(): Position
    {
        return $this->cursor;
    }

    public function insert(string $text, int $length = 0): void
    {
        $line = $this->resolveLine();
        $line = sprintf(
            '%s%s%s',
            substr($line, 0, $this->cursor->x),
            $text,
            substr($line, $this->cursor->x + $length, strlen($line) - $this->cursor->x),
        );
        $this->cursor->x += mb_strlen($text);
        $this->setLine($line);
    }

    public function delete(): void
    {
    }

    public function startOfLine(): void
    {
    }

    public function deleteWord(): void
    {
    }

    public function cursorLeft(): void
    {
    }

    public function cursorRight(): void
    {
    }

    public function cursorDown(): void
    {
    }

    private function resolveLine(): string
    {
        $position = $this->cursor;

        // if the line at the cursor doesn't exist
        if (!isset($this->lines[$position->y])) {
            throw new RuntimeException(sprintf(
                'There is no line at position: %d', $position->y
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
