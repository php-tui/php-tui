---
title:  Sprite 
description: Renders a "sprite" based on a given "ascii art"
---
##  Sprite 

`PhpTui\Tui\Extension\Core\Shape\SpriteShape`

Renders a "sprite" based on a given "ascii art"


Each sprite can have a single color but they can be layered on the canvas.

### Example

{{% terminal file="/data/example/docs/shape/spriteShape.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/spriteShape.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **rows** | `array` | Set of lines/rows which make up the Sprite. e.g. `['    ', '  x  ']`. The lines do not have to be of equal length. |
| **color** | `PhpTui\Tui\Model\Color` | Color of the sprite |
| **position** | `PhpTui\Tui\Model\Position\FloatPosition` | Position to place the sprite at (bottom left) |
| **alphaChar** | `string` | Character to use as the "alpha" (transparent) "channel". Defaults to empty space. |
| **xScale** | `float` | X scale |
| **yScale** | `float` | Y scale |