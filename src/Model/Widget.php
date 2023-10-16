<?php

namespace DTL\PhpTui\Model;

interface Widget
{
    public function render(Area $area, Buffer $buffer): void;
}
