<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Model\Text\Text;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()->build();
$display->draw(
    ParagraphWidget::fromText(
        Text::fromString(
            <<<'EOT'
                Once upon a midnight weary,
                While I pondered weak and weary,
                Over many a quaint and curious volume of forgotten lore.
                EOT
        )
    )
);
