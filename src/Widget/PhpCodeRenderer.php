<?php

namespace PhpTui\Tui\Widget;

use PhpTui\Tui\Model\AnsiColor;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Color;
use PhpTui\Tui\Model\Position;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\Widget\Line;
use PhpTui\Tui\Model\Widget\Span;

class PhpCodeRenderer implements WidgetRenderer
{
    public function __construct()
    {
        $this->colors = [
            'T_CONSTANT_ENCAPSED_STRING' => AnsiColor::Green,
        ];
    }
    public function render(WidgetRenderer $renderer, Widget $widget, Area $area, Buffer $buffer): void
    {
        if (!$widget instanceof PhpCode) {
            return;
        }
        $spans = [];
        $x = 0;
        $y=  0;
        $newLine = false;
        $extraSpace = '';
        $position = Position::at($x, $y);
        foreach (token_get_all($widget->code) as $token) {
            if (is_int($token[0])) {
                $span = Span::fromString(
                    $token[1]
                )->style(
                    Style::default()->fg($this->colors[token_name($token[0])]??AnsiColor::Reset)
                );
                $width = mb_strlen($token[1]);
                if (str_contains($token[1], "\n")) {
                    $newLine = true;
                    $extraSpace = substr($token[1], strrpos($token[1], "\n") + 1);
                }
            } else {
                $span = Span::fromString($token);
                $width = mb_strlen($token);
            }
            $position = $position->withX($x);
            $position = $position->withY($y);
            $buffer->putSpan($position, $span, PHP_INT_MAX);
            $x += $width;
            if ($newLine) {
                $y++;
                $x = 0 + mb_strlen($extraSpace);
                $newLine = false;
                $extraSpace = '';
            }
        }
    }
}
