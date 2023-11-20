<?php

declare(strict_types=1);

namespace PhpTui\Docgen\Renderer;

use PhpTui\Docgen\DocRenderer;

class AggregateDocRenderer implements DocRenderer
{
    /**
     * @var DocRenderer[]
     */
    private array $renderers;

    public function __construct(DocRenderer ...$renderers)
    {
        $this->renderers = $renderers;
    }

    public function render(DocRenderer $renderer, object $object): ?string
    {
        foreach ($this->renderers as $child) {
            if (null !== $out = $child->render($this, $object)) {
                return $out;
            }
        }

        return '';
    }
}
