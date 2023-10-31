<?php

namespace PhpTui\Tui\Example\Demo\Page;

use PhpTui\Term\Event;
use PhpTui\Tui\Example\Demo\Component;
use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Widget\Span;
use PhpTui\Tui\Widget\RawWidget;

class ColorsPage implements Component
{
    public function build(): Widget
    {
        return RawWidget::new(function (Buffer $buffer): void {
            $this->write16Colors($buffer);
        });
    }

    public function handle(Event $event): void
    {
    }

    private function write16Colors(Buffer $buffer): void
    {
        $x = 0;
        $y = 0;
        for ($i = 0; $i < 15; $i++) {
            $color = AnsiColor::fromIndex($i);
            $name = $color->name;
            $buffer->putSpan(
                Position::at($x, $y),
                Span::styled($name, Style::default()->bg($color)),
                strlen($name)
            );
            $x += strlen($name);
        }
    }
}
