<?php

namespace PhpTui\Tui\Example\Demo;

final class AppState
{
    public function __construct(
        public ActivePage $activePage = ActivePage::Home,
    ) {}
}
