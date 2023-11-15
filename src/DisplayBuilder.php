<?php

namespace PhpTui\Tui;

use PhpTui\Tui\Adapter\ImageMagick\ImageMagickExtension;
use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\Canvas\AggregateShapePainter;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Canvas\ShapeSet;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\DisplayExtension;
use PhpTui\Tui\Model\Viewport;
use PhpTui\Tui\Model\Viewport\Fixed;
use PhpTui\Tui\Model\Viewport\Fullscreen;
use PhpTui\Tui\Model\Viewport\Inline;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\WidgetSet;
use PhpTui\Tui\Model\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Shape\DefaultShapeSet;
use PhpTui\Tui\Widget\CanvasRenderer;
use PhpTui\Tui\Widget\DefaultWidgetSet;

/**
 * An entry point for PHP-TUI.
 *
 * You can use this class to get the Display object
 * upon which you can start rendering widgets.
 *
 * ```
 * $display = DisplayBuilder::default()->build();
 * $display->drawWidget(
 *    Paragraph::fromString("Hello World")
 * );
 * ```
 * By default it will use the PhpTermBackend in fullscreen mode.
 *
 * You can add additional widgets and shapes with this builder.
 */
final class DisplayBuilder
{
    /**
     * @var ShapePainter[]
     */
    private array $shapePainters = [];
    /**
     * @var WidgetRenderer[]
     */
    private array $widgetRenderers = [];
    /**
     * @param DisplayExtension[] $extensions
     */
    private function __construct(
        private Backend $backend,
        private ?Viewport $viewport,
        private array $extensions
    ) {
    }

    /**
     * Return a default display using the fullscreen
     */
    public static function default(?Backend $backend = null): self
    {
        return self::new($backend, null, [
            new CoreExtension(),
        ]);
    }


    /**
     * Explicitly require a fullscreen viewport
     */
    public function fullscreen(): self
    {
        $this->viewport = new Fullscreen();
        return $this;
    }

    /**
     * Build and return the Display.
     */
    public function build(): Display
    {
        foreach ($this->extensions as $extension) {
            foreach ($extension->shapePainters() as $shapePainter) {
                $this->shapePainters[] = $shapePainter;
            }
            foreach ($extension->widgetRenderers() as $widgetRenderers) {
                $this->widgetRenderers[] = $widgetRenderers;
            }
        }
        return Display::new(
            $this->backend,
            $this->viewport ?? new Fullscreen(),
            new AggregateWidgetRenderer([
                ...$this->shapePainters ? [$this->buildCanvasRenderer()] : [],
                ...$this->widgetRenderers,
            ])
        );
    }

    public function inline(int $height): self
    {
        $this->viewport = new Inline($height);
        return $this;
    }

    public function fixed(int $width, int $height): self
    {
        $this->viewport = new Fixed(Area::fromDimensions($width, $height));
        return $this;
    }

    public function addShapePainter(ShapePainter $shapePainter): self
    {
        $this->shapePainters[] = $shapePainter;

        return $this;
    }

    private function buildCanvasRenderer(): CanvasRenderer
    {
        return new CanvasRenderer(new AggregateShapePainter($this->shapePainters));
    }

    /**
     * @param DisplayExtension[] $extensions
     */
    public static function new(?Backend $backend, ?Viewport $viewport, array $extensions = []): self
    {
        return new self(
            $backend ?? PhpTermBackend::new(),
            $viewport,
            $extensions,
        );
    }

    public function addExtension(DisplayExtension $extension): self
    {
        $this->extensions[] = $extension;

        return $this;
    }
}
