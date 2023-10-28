<?php

namespace PhpTui\Tui\Model;

use Closure;
use PhpTui\Tui\Model\Viewport\Fullscreen;
use PhpTui\Tui\Model\Viewport\Inline;

final class Display
{
    /**
     * @param array{Buffer,Buffer} $buffers
     */
    public function __construct(
        private Backend $backend,
        private array $buffers,
        private int $current,
        /** @phpstan-ignore-next-line */
        private bool $hiddenCursor,
        private Viewport $viewport,
        private Area $viewportArea,
        private Area $lastKnownSize,
        private Position $lastKnownCursorPosition,
    ) {
    }

    public static function fullscreen(Backend $backend): self
    {
        $size = $backend->size();
        return new self(
            $backend,
            [Buffer::empty($size), Buffer::empty($size)],
            0,
            false,
            new Fullscreen(),
            $size,
            $size,
            new Position(0, 0),
        );
    }

    public function flush(): void
    {
        $previous = $this->buffers[1 - $this->current];
        $current = $this->buffers[$this->current];
        $updates = $previous->diff($current);
        if (count($updates) > 0) {
            $this->lastKnownCursorPosition = $updates->last()->position;
        }

        $this->backend->draw($updates);
    }

    public function buffer(): Buffer
    {
        return $this->buffers[$this->current];
    }

    /**
     * @param Closure(Buffer): void $closure
     */
    public function draw(Closure $closure): void
    {
        $this->autoresize();
        $closure($this->buffer());

        $this->flush();
        $this->swapBuffers();
        $this->backend->flush();
    }

    private function autoresize(): void
    {
        if (!$this->viewport instanceof Fullscreen && !$this->viewport instanceof Inline) {
            return;
        }

        $size = $this->backend->size();
        if ($size == $this->lastKnownSize) {
            return;
        }

        $this->resize($size);
    }

    private function resize(Area $size): void
    {
        $offsetInPreviousViewport = max(0, $this->lastKnownCursorPosition->y - $this->viewportArea->top());
        $nextArea = $this->viewport->computeArea($this->backend, $size, $offsetInPreviousViewport);

        $this->setViewportArea($nextArea);
        $this->clear();
        $this->lastKnownSize = $size;
    }

    private function setViewportArea(Area $area): void
    {
        $this->buffers[$this->current]->resize($area);
        $this->buffers[1 - $this->current]->resize($area);
        $this->viewportArea = $area;
    }

    private function swapBuffers(): void
    {
        /** @phpstan-ignore-next-line */
        $this->buffers[1 - $this->current] = Buffer::empty($this->viewportArea);
        $this->current = 1 - $this->current;
    }

    private function clear(): void
    {
        $this->backend->clearRegion(ClearType::ALL);
    }
}
