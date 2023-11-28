<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\TextArea\Widget;

final class TextAreaState
{
    public function __construct(
        public int $viewportOffset
    ) {
    }
}
