<?php

namespace DTL\PhpTui\Widget\Canvas;

abstract class Grid
{
    abstract public function resolution(): Resolution;

    abstract public function save(): Layer;

    abstract public function reset(): void;
}
