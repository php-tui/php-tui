<?php

declare(strict_types=1);

namespace PhpTui\Tui\Model\Viewport;

use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Display\Backend;
use PhpTui\Tui\Model\Display\ClearType;
use PhpTui\Tui\Model\Display\Viewport;
use PhpTui\Tui\Model\Position\Position;

/**
 * Viewport that is displayed _after_ the cursor's current position.
 *
 * You can use this viewport in with `Display#insertBefore` in order to add content
 * before the viewport, which can be usedful for "logging" progress.
 */
final class Inline implements Viewport
{
    public function __construct(
        /**
         * Height of the viewport
         */
        public readonly int $height
    ) {
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

    public function clear(Backend $backend, Area $area): void
    {
        $backend->moveCursor(Position::at($area->left(), $area->top() + 1));
        $backend->clearRegion(ClearType::AfterCursor);
    }
}
