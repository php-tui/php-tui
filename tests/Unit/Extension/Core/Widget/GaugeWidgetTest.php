<?php

declare(strict_types=1);

namespace PhpTui\Tui\Tests\Unit\Extension\Core\Widget;

use PhpTui\Tui\Extension\Core\Widget\GaugeWidget;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class GaugeWidgetTest extends TestCase
{
    public function testInvalidRange(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Gauge ratio must be between 0 and 1 got 2.500000');
        GaugeWidget::default()->ratio(2.5);
    }
}
