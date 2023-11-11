<?php

namespace PhpTui\Tui\Model;

use Closure;
use PhpTui\Tui\Adapter\Bdf\BdfShapeSet;
use PhpTui\Tui\Adapter\Bdf\FontRegistry;
use PhpTui\Tui\Adapter\ImageMagick\ImageMagickShapeSet;
use PhpTui\Tui\Model\Canvas\AggregateShapePainter;
use PhpTui\Tui\Model\Viewport\Fullscreen;
use PhpTui\Tui\Model\Viewport\Inline;
use PhpTui\Tui\Model\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Model\WidgetRenderer\NullWidgetRenderer;
use PhpTui\Tui\Shape\DefaultShapeSet;
use PhpTui\Tui\Widget\DefaultWidgetSet;

final class Display
{
    /**
     * @param array<int,Buffer> $buffers
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
        private WidgetRenderer $widgetRenderer,
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
            AggregateWidgetRenderer::fromWidgetSets(
                new DefaultWidgetSet(
                    AggregateShapePainter::fromShapeSets(
                        new DefaultShapeSet(),
                        new BdfShapeSet(FontRegistry::default()),
                        new ImageMagickShapeSet(),
                    )
                )
            )
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
     * Synchronizes terminal size, calls the rendering closure, flushes the current internal state
     * and prepares for the next draw call.
     *
     * @param Closure(Buffer): void $closure
     */
    public function draw(Closure $closure): void
    {
        $this->autoresize();
        $closure($this->buffer());

        $this->flush();
        $this->backend->flush();
        $this->swapBuffers();
    }

    /**
     * Synchronizes terminal size, renders the given widget, flushes the current internal state
     * and prepares for the next draw call.
     *
     * This is the same as Draw but instead of a closure you pass a single
     * widget (usually a Grid widget).
     */
    public function drawWidget(Widget $widget): void
    {
        $buffer = $this->buffer();
        $this->draw(function () use ($widget, $buffer): void {
            $this->widgetRenderer->render(
                new NullWidgetRenderer(),
                $widget,
                $buffer->area(),
                $buffer
            );
        });
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
        $this->buffers[$this->current] = Buffer::empty($area);
        $this->buffers[1 - $this->current] = Buffer::empty($area);
        $this->viewportArea = $area;
    }

    private function swapBuffers(): void
    {
        $this->buffers[1 - $this->current] = Buffer::empty($this->viewportArea);
        $this->current = 1 - $this->current;
    }

    private function clear(): void
    {
        $this->backend->clearRegion(ClearType::ALL);
    }
}
