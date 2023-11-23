<?php

declare(strict_types=1);

namespace PhpTui\Tui\Extension\TextArea;

use PhpTui\Tui\Extension\TextArea\Widget\TextAreaRenderer;
use PhpTui\Tui\Model\Display\DisplayExtension;

class TextAreaExtension implements DisplayExtension
{
    public function shapePainters(): array
    {
        return [];
    }

    public function widgetRenderers(): array
    {
        return [
            new TextAreaRenderer(),
        ];
    }
}
