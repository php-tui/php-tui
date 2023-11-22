<?php

namespace PhpTui\Tui\Tests\Unit\Extension\TextEditor;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Extension\TextEditor\TextEditor;

class TextEditorTest extends TestCase
{
    public function testInsert(): void
    {
        $editor = TextEditor::fromString('');
        $editor->insert('H');
        $editor->insert('ello');
        $editor->insert(' World');
        self::assertEquals('Hello World', $editor->toString());

        $editor = TextEditor::fromString('Hello World');
        $editor->insert('Hello ');
        self::assertEquals('Hello Hello World', $editor->toString());
    }

    public function testInsertReplace(): void
    {
        $editor = TextEditor::fromString('Hello World');
        $editor->insert('World', 5);
        self::assertEquals('World World', $editor->toString());
    }
}
