---
title:  Map 
description: Renders a map of the world!
---
##  Map 

`PhpTui\Tui\Extension\Core\Shape\MapShape`

Renders a map of the world!
{{% terminal file="/data/example/docs/shape/mapShape.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/mapShape.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the constructor arguments named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **mapResolution** | `PhpTui\Tui\Extension\Core\Shape\MapResolution` | Resolution of the map (enum low or high) |
| **color** | `PhpTui\Tui\Model\Color` | Color of the map |