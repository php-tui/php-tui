<?php

namespace PhpTui\Term;

final class KeyModifiers
{
    const SHIFT = 0b0000_0001;
    const CONTROL = 0b0000_0010;
    const ALT = 0b0000_0100;
    const SUPER = 0b0000_1000;
    const HYPER = 0b0001_0000;
    const META = 0b0010_0000;
    const NONE = 0b0000_0000;
}
