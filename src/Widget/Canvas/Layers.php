<?php

namespace DTL\PhpTui\Widget\Canvas;

final class Layers
{
    public static function none(): self
    {
        return new self();
    }

    public function add(Layer $layer): void
    {
        $this->layers[] = $layer;
    }
}
