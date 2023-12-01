<?php

declare(strict_types=1);

namespace PhpTui\Tui\Display;

use PhpTui\Tui\Position\Position;

interface Backend
{
    public function size(): Area;

    public function draw(BufferUpdates $updates): void;

    public function flush(): void;

    public function clearRegion(ClearType $type): void;

    public function cursorPosition(): Position;

    /**
     * @param int<0,max> $linesAfterCursor
     */
    public function appendLines(int $linesAfterCursor): void;

    public function moveCursor(Position $position): void;
}
