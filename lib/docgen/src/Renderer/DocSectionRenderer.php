<?php

declare(strict_types=1);

namespace PhpTui\Docgen\Renderer;

use PhpTui\Docgen\DocRenderer;
use PhpTui\Docgen\DocSection;

class DocSectionRenderer implements DocRenderer
{
    public function render(DocRenderer $renderer, object $object): ?string
    {
        if (!$object instanceof DocSection) {
            return null;
        }

        $out = [];
        $out[] = '---';
        $out[] = 'bookCollapseSection: true';
        $out[] = sprintf('title: %s',$object->title);
        $out[] = '---';
        $out[] = $object->description;

        return implode("\n", $out);
    }
}
