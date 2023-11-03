<?php

namespace PhpTui\Tui\Example\Slideshow;

use PhpTui\Tui\Model\Widget;

interface Slide
{
    public function title(): string;
    public function build(): Widget;
}
