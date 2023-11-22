<?php

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarOrientation;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarSymbols;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Widget;

final class ScrollbarWidget implements Widget
{
    public function __construct(
        public ScrollbarOrientation $orientation,
        public Style $thumbStyle,
        public string $thumbSymbol,
        public Style $trackStyle ,
        public ?string $trackSymbol,
        public ?string $beginSymbol,
        public Style $beginStyle,
        public ?string $endSymbol,
        public Style $endStyle
    ) {}

    public static function default(): self
    {
        $symbols = ScrollbarSymbols::doubleVertical();
        return new self(
            orientation: ScrollbarOrientation::VerticalLeft,
            thumbStyle: Style::default(),
            thumbSymbol: $symbols->thumb,
            trackStyle: Style::default(),
            trackSymbol: $symbols->track,
            beginSymbol: $symbols->begin,
            beginStyle: Style::default(),
            endSymbol: $symbols->end,
            endStyle: Style::default()
        );
    }


}
