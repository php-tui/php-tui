<?php

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use PHPUnit\Framework\TestCase;
use PhpTui\Tui\Extension\Core\Widget\GaugeWidget;
use RuntimeException;

class GaugeWidgetTest extends TestCase
{
    public function testInvalidRange(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Gauge ratio must be between 0 and 1 got 2.500000');
        GaugeWidget::default()->ratio(2.5);
    }
}
