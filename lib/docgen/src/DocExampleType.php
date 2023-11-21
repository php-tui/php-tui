<?php

declare(strict_types=1);

namespace PhpTui\Docgen;

enum DocExampleType
{
    case CodeAndOutput;
    case CodeOnly;
    case None;

}
