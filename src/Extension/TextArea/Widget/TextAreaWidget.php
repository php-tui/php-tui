<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\TextArea\Widget;

use PhpTui\Tui\Extension\TextArea\TextEditor;
use PhpTui\Tui\Model\Widget;

final class TextAreaWidget implements Widget
{
    public function __construct(
        public TextEditor $editor,
        public TextAreaState $state,
    ) {
    }

    public static function fromString(string $contents): self
    {
        $textArea = TextEditor::fromString($contents);

        return new self(
            $textArea,
            new TextAreaState(0),
        );
    }

    public static function fromEditor(TextEditor $textArea): self
    {
        return new self($textArea, new TextAreaState(0));
    }

    public function state(TextAreaState $textAreaState): self
    {
        $this->state = $textAreaState;

        return $this;
    }

}
