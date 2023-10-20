<?php

namespace DTL\PhpTui\Widget\Table;

use DTL\PhpTui\Model\Style;
use DTL\PhpTui\Model\Widget\Text;

final class TableItem
{
    public function __construct(
        public Text $content,
        public Style $style
    ) {
    }

    public function height(): int
    {
        return $this->content->height();
    }
}
