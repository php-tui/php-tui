<?php

namespace PhpTui\Tui\Model;

interface Widget
{
    public function render(Area $area, Buffer $buffer): void;
}
