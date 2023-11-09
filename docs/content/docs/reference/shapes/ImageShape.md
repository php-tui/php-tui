---
title: ImageShape
description: Renders an image on the canvas.
---
## ImageShape

Renders an image on the canvas.
{{% terminal file="/data/example/docs/shape/imageShape.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/imageShape.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the constructor arguments named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **path** | `string` | Absolute path to the image |
| **position** | `PhpTui\Tui\Model\Widget\FloatPosition` | Position to render at (bottom left) |