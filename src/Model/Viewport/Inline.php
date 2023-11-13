<?php

namespace PhpTui\Tui\Model\Viewport;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\Exception\TodoException;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Viewport;

final class Inline implements Viewport
{
    public function __construct(public readonly int $height)
    {
    }

    public function size(Backend $backend): Area
    {
        return $backend->size();
    }

    public function cursorPos(Backend $backend): Position
    {
        return $backend->cursorPosition();
    }

    public function area(Backend $backend, int $offsetInPreviousViewport): Area
    {
        $size = $backend->size();
        $pos = $backend->cursorPosition();
        $row = $pos->y;
        $maxHeight = min($size->height, $this->height);
        $linesAfterCursor = max(0, $this->height - $offsetInPreviousViewport - 1);
        $backend->appendLines($linesAfterCursor);
        $availableLines = max(0, $size->height - $row - 1);
        $missingLines = max(0, $linesAfterCursor - $availableLines);
        if ($missingLines > 0) {
            $row = max(0, $row - $missingLines);
        }
        $row = max(0, $row - $offsetInPreviousViewport);

        return Area::fromScalars(0, $row, $size->width, $maxHeight);
    }
}
