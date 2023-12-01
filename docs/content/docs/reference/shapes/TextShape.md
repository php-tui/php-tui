---
title:  Text 
description: Renders text on the canvas.
---
##  Text 

`PhpTui\Tui\Extension\Bdf\Shape\TextShape`

Renders text on the canvas.


This widget requires a bitmap font in the BDF format.
You can use the `PhpTui\Tui\Adapter\Bdf\FontRegistry` to
 load and manage fonts. It has a default font built in.

### Example

{{% terminal file="/data/example/docs/shape/textShape.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/textShape.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **font** | `string` | Font name as it is known in the font registry |
| **text** | `string` | Text to render |
| **color** | `PhpTui\Tui\Color\Color` | Color of the text |
| **position** | `PhpTui\Tui\Position\FloatPosition` | Position of the text (bottom left corner) |
| **scaleX** | `float` | Horizontal scale of the font |
| **scaleY** | `float` | Verttical scale of the font |