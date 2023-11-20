---
title:  Image 
description: Renders an image on the canvas.
---
##  Image 

`PhpTui\Tui\Extension\ImageMagick\Shape\ImageShape`

Renders an image on the canvas.

Renders an image on the canvas.
### Example

{{% terminal file="/data/example/docs/shape/imageShape.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/imageShape.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **path** | `string` | Absolute path to the image |
| **position** | `PhpTui\Tui\Model\Position\FloatPosition` | Position to render at (bottom left) |