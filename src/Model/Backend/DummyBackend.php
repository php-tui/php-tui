<?php

namespace DTL\PhpTui\Model\Backend;

use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Backend;

class DummyBackend implements Backend
{
    public function __construct(private Area $size) {
    }

    public function size(): Area
    {
        return $this->size;
    }
}
