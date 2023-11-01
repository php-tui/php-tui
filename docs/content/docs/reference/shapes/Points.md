## Points

Render a set of points on the canvas.
### Parameters

Configure the shape using the constructor arguments named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **coords** | `array` | Set of coordinates to draw, e.g. `[[0.0, 0.0], [2.0, 2.0], [4.0,4.0]]` |
| **color** | `PhpTui\Tui\Model\Color` | Color of the points |
### Example
The following code example:

{{% codeInclude file="/data/example/docs/shape/points.php" language="php" %}}

Should render as:

{{% terminal file="/data/example/docs/shape/points.snapshot" %}}