<?php

namespace PhpTui\Tui;

use PhpTui\Tui\Adapter\PhpTerm\PhpTermBackend;
use PhpTui\Tui\Extension\Core\CoreExtension;
use PhpTui\Tui\Model\Area;
use PhpTui\Tui\Model\Backend;
use PhpTui\Tui\Model\Canvas\AggregateShapePainter;
use PhpTui\Tui\Model\Canvas\ShapePainter;
use PhpTui\Tui\Model\Display;
use PhpTui\Tui\Model\DisplayExtension;
use PhpTui\Tui\Model\Viewport;
use PhpTui\Tui\Model\Viewport\Fixed;
use PhpTui\Tui\Model\Viewport\Fullscreen;
use PhpTui\Tui\Model\Viewport\Inline;
use PhpTui\Tui\Model\WidgetRenderer;
use PhpTui\Tui\Model\WidgetRenderer\AggregateWidgetRenderer;
use PhpTui\Tui\Model\Widget\CanvasRenderer;

/**
 * An entry point for PHP-TUI.
 *
 * You can use this class to get the Display object
 * upon which you can start rendering widgets.
 *
 * ```
 * $display = DisplayBuilder::default()->build();
 * $display->draw(
 *    Paragraph::fromString("Hello World")
 * );
 * ```
 *
 * By default it will use the PhpTermBackend in fullscreen mode.
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
     * Return a new display with no extensions.
     *
     * @param DisplayExtension[] $extensions
     */
    public static function new(?Backend $backend, array $extensions = []): self
    {
        return new self(
            $backend ?? PhpTermBackend::new(),
            null,
            $extensions,
        );
    }

    /**
     * Return a default display with the core extension.
     */
    public static function default(?Backend $backend = null): self
    {
        return self::new($backend, [
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
     * When set the display will be of the specified height _after_ the row
     * that the cursor is on.
     */
    public function inline(int $height): self
    {
        $this->viewport = new Inline($height);
        return $this;
    }

    /**
     * When set the display will be at the specified (x,y) position with the
     * specified width and height.
     */
    public function fixed(int $x, int $y, int $width, int $height): self
    {
        $this->viewport = new Fixed(Area::fromScalars($x, $y, $width, $height));
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

    /**
     * Add a shape painter.
     *
     * When at least one shape painter is added the Canvas widget will
     * automatically be enabled.
     */
    public function addShapePainter(ShapePainter $shapePainter): self
    {
        $this->shapePainters[] = $shapePainter;

        return $this;
    }

    /**
     * Add a widget renderer
     */
    public function addWidgetRenderer(WidgetRenderer $widgetRenderer): self
    {
        $this->widgetRenderers[] = $widgetRenderer;

        return $this;
    }

    /**
     * Add a display extension.
     */
    public function addExtension(DisplayExtension $extension): self
    {
        $this->extensions[] = $extension;

        return $this;
    }

    private function buildCanvasRenderer(): CanvasRenderer
    {
        return new CanvasRenderer(new AggregateShapePainter($this->shapePainters));
    }
}
