## Canvas

The canvas widget provides a surface, of arbitrary scale, upon which shapes can be drawn.
{{% terminal file="/data/example/docs/widget/canvas.snapshot" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/canvas.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **xBounds** | `PhpTui\Tui\Model\AxisBounds` | Bounds of the X Axis. Must be set if the canvas is to render. |
| **yBounds** | `PhpTui\Tui\Model\AxisBounds` | Bounds of the Y Axis. Must be set if the canvas is to render. |
| **painter** | `Closure(CanvasContext): void` | The painter closure can draw shapes onto the canvas. |
| **backgroundColor** | `PhpTui\Tui\Model\Color` | Background color |
| **marker** | `PhpTui\Tui\Model\Marker` | The marker type to use, e.g. `Marker::Braille` |