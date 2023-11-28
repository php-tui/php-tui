<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\TextArea\Widget;

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\TextArea\TextEditor;
use PhpTui\Tui\Extension\TextArea\TextAreaExtension;
use PhpTui\Tui\Extension\TextArea\Widget\TextAreaWidget;
use PhpTui\Tui\Model\Display\Backend\DummyBackend;
use PhpTui\Tui\Model\Widget;
use PHPUnit\Framework\TestCase;

class TextAreaRendererTest extends TestCase
{
    public function testRender(): void
    {
        $text = <<<'EOT'
            Let us go then you and I
            When the evening is spread out against the sky
            Like a patient etherized upon a table
            EOT
        ;

        self::assertEquals(
            [
            ' ▲  Let us',
            ' █  When t',
            ' █  Like a',
            ' █        ',
            ' ▼        ',
            ],
            $this->render(TextAreaWidget::fromString($text)),
        );
    }

    public function testSrollbarAtMax(): void
    {
        $text = <<<'EOT'
            Let us go then you and I
            When the evening is spread out against the sky
            Like a patient etherized upon a table
            Let us go through half-deserted streets
            The muttering retreats
            Of restless nights in one night cheap hotels
            And swadust restaurants with oyster shells
            Streets that follow like a tedious argument
            Of insidiuous intent
            Oh! do not ask what is it!
            Let us go and make our visit...
            EOT
        ;
        $editor = TextEditor::fromString($text);
        $editor->cursorDown(100);

        self::assertEquals(
            [
                ' ▲  And sw',
                ' ║  Street',
                ' ║  Of ins',
                ' █  Oh! do',
                ' ▼  Let us',
            ],
            $this->render(TextAreaWidget::fromEditor($editor)),
        );
    }

    public function testRenderScrollViewportDown(): void
    {
        $text = <<<'EOT'
            Let us go then you and I
            When the evening is spread out against the sky
            Like a patient etherized upon a table
            Let us go through underserted streets
            The muttering retreats
            Of restless nights in one night cheap hotels
            EOT
        ;

        $widget = TextAreaWidget::fromString($text);
        $widget->editor->cursorDown(4);
        self::assertEquals(
            [
            ' ▲  Like a',
            ' █  Let us',
            ' ▼  The mu',
            ],
            $this->render($widget, 3),
        );
    }

    public function testDoNotScrollUpUnlessCursorIsGoingOutOfBounds(): void
    {
        $text = <<<'EOT'
            Let us go then you and I
            When the evening is spread out against the sky
            Like a patient etherized upon a table
            Let us go through underserted streets
            The muttering retreats
            Of restless nights in one night cheap hotels
            EOT
        ;

        $widget = TextAreaWidget::fromString($text);
        $widget->editor->cursorDown(5);
        self::assertEquals(
            [
            ' ▲  Let us',
            ' █  The mu',
            ' ▼  Of res',
            ],
            $this->render($widget, 3),
        );
        $widget->editor->cursorUp(2);
        self::assertEquals(
            [
            ' ▲  Let us',
            ' █  The mu',
            ' ▼  Of res',
            ],
            $this->render($widget, 3),
        );
        $widget->editor->cursorUp(5);
        self::assertEquals(
            [
            ' ▲  Let us',
            ' █  When t',
            ' ▼  Like a',
            ],
            $this->render($widget, 3),
        );
    }

    /**
     * @return string[]
     */
    private function render(Widget $widget, int $height = 5): array
    {
        $backend = DummyBackend::fromDimensions(10, $height);
        DisplayBuilder::default($backend)->addExtension(
            new TextAreaExtension()
        )->build()->draw($widget);

        return $backend->toLines();
    }
}
