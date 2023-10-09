<?php

namespace DTL\PhpTui\Model;

use Countable;
use RuntimeException;

final class Buffer implements Countable
{
    /**
     * @param Cell[] $content
     */
    public function __construct(
        private readonly Area $area,
        private readonly array $content
    ) {
    }

    public static function empty(Area $area): self
    {
        return new self($area, array_fill(0, $area->area(), Cell::empty()));
    }

    public static function filled(Area $area, Cell $cell): self
    {
        return new self($area, array_fill(0, $area->area(), $cell));
    }

    /**
     * @param string[] $lines
     */
    public static function fromLines(array $lines): self
    {
        $height = count($lines);
        $width = array_reduce(
            $lines,
            fn ($acc, $line) => mb_strlen($line) > $acc ? mb_strlen($line) : $acc,
            0
        );

        $buffer = self::empty(Area::fromPrimatives(0, 0, $width, $height));
        foreach ($lines as $y => $line) {
            $buffer->putString(new Position(0, $y), $line, Style::default());
        }

        return $buffer;
    }

    /**
     * @return Cell[]
     */
    public function content(): array
    {
        return $this->content;
    }

    /**
     * @return BufferUpdate[]
     */
    public function diff(Buffer $buffer): array
    {
        $previous = $this->content();
        $next     = $buffer->content();
        $updates = [];
        $toSkip = 0;
        $invalidated = 0;

        for ($i = 0; $i < count($next); $i++) {
            $previousCell = $previous[$i];
            $currentCell = $next[$i];
            if (($previousCell->char !== $currentCell->char || $invalidated > 0) && $toSkip === 0) {
                $updates[] = new BufferUpdate(Position::fromIndex($i, $this->area), $currentCell->char);
            }

            $toSkip = strlen($currentCell->char) - 1;
            $toSkip = $toSkip < 0 ? 0 : $toSkip;
            $affectedWidth = max(strlen($currentCell->char), strlen($previousCell->char));
            $invalidated = max($affectedWidth, $invalidated) - 1;
            $invalidated < 0 ? 0 : $invalidated;
        }

        return $updates;

    }

    public function count(): int
    {
        return count($this->content);
    }

    private function putString(Position $position, string $line, Style $style): void
    {
        $index = $position->toIndex($this->area);
        throw new RuntimeException('asd');
    }
}
