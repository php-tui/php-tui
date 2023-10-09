<?php

namespace DTL\PhpTui\Model;

interface Backend
{
    public function size(): Area;

    public function draw(BufferUpdates $updates): void;
}
