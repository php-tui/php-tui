<?php

namespace PhpTui\Tui\Tests\Unit\Extension\TextArea\Widget;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\TextArea\TextArea;
use PhpTui\Tui\Extension\TextArea\TextAreaExtension;
use PhpTui\Tui\Extension\TextArea\Widget\TextAreaWidget;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Display\Backend\DummyBackend;
use PhpTui\Tui\Model\Display\Buffer;
use PhpTui\Tui\Model\Widget;

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

        self::assertEquals([
            '          ',
            '  Let us g',
            '          ',
            '          ',
            '          ',
            ],
            $this->render(TextAreaWidget::fromString($text)),
        );
    }

    private function render(Widget $widget): array
    {
        $backend = DummyBackend::fromDimensions(10, 5);
        DisplayBuilder::default($backend)->addExtension(new TextAreaExtension())->build()->draw($widget);

        return $backend->toLines();
    }
}
