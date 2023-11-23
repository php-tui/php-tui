<?php

declare(strict_types=1);

use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\TextArea\TextAreaExtension;
use PhpTui\Tui\Extension\TextArea\Widget\TextAreaWidget;

require 'vendor/autoload.php';

$display = DisplayBuilder::default()
    ->addExtension(new TextAreaExtension())
    ->build();
$display->draw(
    TextAreaWidget::fromString(
        <<<'EOT'
            Hear the sledges with the bells—
                        Silver bells!
            What a world of merriment their melody foretells!
                How they tinkle, tinkle, tinkle,
                   In the icy air of night!
                While the stars that oversprinkle
                All the heavens, seem to twinkle
                   With a crystalline delight;
                 Keeping time, time, time,
                 In a sort of Runic rhyme,
            EOT
    )
);
