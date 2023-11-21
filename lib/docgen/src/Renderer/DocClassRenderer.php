<?php

declare(strict_types=1);

namespace PhpTui\Docgen\Renderer;

use PhpTui\Docgen\DocClass;
use PhpTui\Docgen\DocExampleType;
use PhpTui\Docgen\DocRenderer;

class DocClassRenderer implements DocRenderer
{
    public function __construct()
    {
    }

    public function render(DocRenderer $renderer, object $object): ?string
    {
        if (!$object instanceof DocClass) {
            return null;
        }

        $title = $object->humanName;
        $doc = [
            '---',
            sprintf('title: %s', $title),
            sprintf('description: %s', $object->summary),
            '---',
            sprintf('## %s', $title),
            '',
            sprintf('`%s`', $object->className),
            '',
            $object->summary,
        ];

        if ($object->documentation !== $object->summary) {
            $doc[] = '';
            $doc[] = $object->documentation;
        }

        if ($object->hasExample !== DocExampleType::None) {
            $doc[] = '### Example';
            $doc[] = '';
            if ($object->hasExample === DocExampleType::CodeAndOutput) {
                $doc[] = sprintf('{{%% terminal file="/data/example/docs/%s/%s.html" %%}}', $object->singular, $object->name);
            }
            $doc[] ='{{< details "Show code"  >}}';
            $doc[] = sprintf('{{%% codeInclude file="/data/example/docs/%s/%s.php" language="php" %%}}', $object->singular, $object->name);
            $doc[] = '';
            $doc[] = '{{< /details >}}';
        }
        if ($object->params) {
            $doc = array_merge($doc, [
            '### Parameters',
            '',
            sprintf('Configure the %s using the builder methods named as follows:', $object->singular),
            '',
            '| Name | Type | Description |',
            '| --- | --- | --- |',
            ]);
            foreach ($object->params as $param) {
                $doc[] = sprintf(
                    '| **%s** | `%s` | %s |',
                    $param->name,
                    str_replace('|', '\|', $param->type),
                    $param->description
                );
            }
        }

        return implode("\n", $doc);

    }
}
