<?php

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Widget\Paragraph;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->drawWidget(
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
