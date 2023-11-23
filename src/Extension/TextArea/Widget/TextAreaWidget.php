<?php

namespace PhpTui\Tui\Extension\TextArea\Widget;

use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarState;
use PhpTui\Tui\Extension\TextArea\TextArea;
use PhpTui\Tui\Model\Widget;

final class TextAreaWidget implements Widget
{
    public function __construct(
        public ?ScrollbarState $scrollbarState,
        public TextArea $editor,
    )
    {
    }

    public static function fromString(string $contents): self
    {
        $textArea = TextArea::fromString($contents);
        return new self(
            new ScrollbarState($textArea->lineCount()),
            $textArea,
        );
    }

}
