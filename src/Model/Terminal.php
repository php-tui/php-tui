<?php

namespace DTL\PhpTui\Model;

use DTL\PhpTui\Model\Viewport\Fullscreen;
use RuntimeException;

class Terminal
{
    /**
     * @param array{Buffer,Buffer} $buffers
     */
    public function __construct(
        private Backend $backend,
        private array $buffers,
        private int $current,
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
        throw new RuntimeException('TODO');
    }
}
