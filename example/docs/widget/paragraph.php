<?php

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\Buffer;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\Widget\Text;
use PhpTui\Tui\Widget\Paragraph;

require 'vendor/autoload.php';

$display = Display::fullscreen(PhpTermBackend::new());
$display->draw(function (Buffer $buffer): void {
    Paragraph::new(
        Text::raw(
            <<<'EOT'
                Once upon a midnight weary,
                While I pondered weak and weary,
                Over many a quaint and curious volume of forgotten lore.
                EOT
        )
    )->render($buffer->area(), $buffer);
});
