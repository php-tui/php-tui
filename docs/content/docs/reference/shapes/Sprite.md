## Sprite

Renders a "sprite" based on a given "ascii art"Each sprite can have a single color but they can be layered on the canvas.
### Parameters

Configure the shape using the constructor arguments named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **rows** | `array` |  |
| **color** | `PhpTui\Tui\Model\Color` |  |
| **position** | `PhpTui\Tui\Model\Widget\FloatPosition` |  |
| **alphaChar** | `string` |  |
| **xScale** | `float` |  |
| **density** | `int` |  |
| **yScale** | `float` |  |
### Example
The following code example:

{{% codeInclude file="/data/example/docs/shape/sprite.php" language="php" %}}

Should render as:

{{% terminal file="/data/example/docs/shape/sprite.snapshot" %}}