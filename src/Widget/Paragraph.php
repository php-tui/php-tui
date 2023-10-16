<?php

namespace DTL\PhpTui\Widget;

use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget\HorizontalAlignment;

class Paragraph
{
    private function __construct(
        private ?Block $block = null,
        private Style $style,
        private ?Wrap $wrap,
        private Text $text,
        /** @var array{int,int} */
        private array $scroll,
        private HorizontalAlignment $alignment
    )
    {
    }

    public static function new(string $text): self
    {
        return new self(
            block: null,
            style: Style::default(),
            wrap: null,
            text: /** DTL\PhpTui\Widget\Text */,
            scroll: [],
            alignment: new HorizontalAlignment()
        )
    }
}
