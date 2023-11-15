<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\Paragraph;
use PhpTui\Tui\Model\Widget\Text;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    Paragraph::fromText(
        Text::fromString(
            <<<'EOT'
                Once upon a midnight weary,
                While I pondered weak and weary,
                Over many a quaint and curious volume of forgotten lore.
                EOT
        )
    )
);
