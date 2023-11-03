---
title: Map
description: Renders a map of the world!
---
## Map

Renders a map of the world!
{{% terminal file="/data/example/docs/shape/map.snapshot" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/map.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the constructor arguments named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **mapResolution** | `PhpTui\Tui\Widget\Canvas\Shape\MapResolution` | Resolution of the map (enum low or high) |
| **color** | `PhpTui\Tui\Model\Color` | Color of the map |