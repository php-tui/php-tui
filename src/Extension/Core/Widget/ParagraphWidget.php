<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\Paragraph\Wrap;
use PhpTui\Tui\Model\HorizontalAlignment;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Line;
use PhpTui\Tui\Model\Text\Span;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Model\Widget;

/**
 * This widget has the ability to show and wrap text.
 */
class ParagraphWidget implements Widget
{
    /** @param array{int,int} $scroll */
    private function __construct(
        public Style $style,
        public ?Wrap $wrap,
        public Text $text,
        public array $scroll,
        public HorizontalAlignment $alignment
    ) {
    }

    public static function fromString(string $string): self
    {
        return self::fromText(Text::fromString($string));
    }

    public static function fromText(Text $text): self
    {
        return new self(
            style: Style::default(),
            wrap: null,
            text: $text,
            scroll: [0,0],
            alignment: HorizontalAlignment::Left,
        );
    }

    public function style(Style $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function alignment(HorizontalAlignment $alignment): self
    {
        $this->alignment = $alignment;

        return $this;
    }

    public function wrap(Wrap $wrap): self
    {
        $this->wrap = $wrap;

        return $this;
    }

    public static function fromLines(Line ...$lines): self
    {
        return self::fromText(Text::fromLines(...$lines));
    }

    public static function fromSpans(Span ...$spans): self
    {
        return self::fromText(Text::fromLine(Line::fromSpans($spans)));
    }
}
