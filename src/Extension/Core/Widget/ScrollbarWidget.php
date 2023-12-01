<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarOrientation;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarState;
use PhpTui\Tui\Extension\Core\Widget\Scrollbar\ScrollbarSymbols;
use PhpTui\Tui\Model\Style\Style;
use PhpTui\Tui\Model\Widget\Widget;

/**
 * A widget to display a scrollbar
 *
 * The following components of the scrollbar are customizable in symbol and style.
 *
 * ```text
 * <--▮------->
 * ^  ^   ^   ^
 * │  │   │   └ end
 * │  │   └──── track
 * │  └──────── thumb
 * └─────────── begin
 * ```
 */
final class ScrollbarWidget implements Widget
{
    public function __construct(
        /**
         * If this is a horizontal or a vertical scrollbar
         */
        public ScrollbarOrientation $orientation,
        /**
         * Style for the thumb
         */
        public Style $thumbStyle,
        /**
         * Symbol for the thumb
         */
        public string $thumbSymbol,
        /**
         * Style for the track
         */
        public Style $trackStyle,
        /**
         * Symbol for the track
         */
        public ?string $trackSymbol,
        /**
         * Beginning symbol, e.g. 👈
         */
        public ?string $beginSymbol,
        /**
         * Style for the beginning symbol
         */
        public Style $beginStyle,
        /**
         * Ending symbol, e.g. 👉
         */
        public ?string $endSymbol,
        /**
         * Style for the ending symbol
         */
        public Style $endStyle,
        /**
         * The state
         */
        public ScrollbarState $state,
    ) {
    }

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
            endStyle: Style::default(),
            state: new ScrollbarState(),
        );
    }

    public function isVertical(): bool
    {
        return match($this->orientation) {
            ScrollbarOrientation::VerticalRight => true,
            ScrollbarOrientation::VerticalLeft => true,
            ScrollbarOrientation::HorizontalBottom => false,
            ScrollbarOrientation::HorizontalTop => false,

        };
    }

    public function beginSymbol(?string $beginSymbol): self
    {
        $this->beginSymbol = $beginSymbol;

        return $this;
    }

    public function endSymbol(?string $endSymbol): self
    {
        $this->endSymbol = $endSymbol;

        return $this;
    }

    public function state(ScrollbarState $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function orientation(ScrollbarOrientation $orientation): self
    {
        $this->orientation = $orientation;
        if ($this->isVertical()) {
            $this->symbols(ScrollbarSymbols::doubleVertical());
        } else {
            $this->symbols(ScrollbarSymbols::doubleHorizontal());
        }

        return $this;
    }

    public function symbols(ScrollbarSymbols $symbols): self
    {
        $this->thumbSymbol = $symbols->thumb;
        if ($this->trackSymbol !== null) {
            $this->trackSymbol = $symbols->track;
        }
        if ($this->beginSymbol !== null) {
            $this->beginSymbol = $symbols->begin;
        }
        if ($this->endSymbol !== null) {
            $this->endSymbol = $symbols->end;
        }

        return $this;
    }

}
