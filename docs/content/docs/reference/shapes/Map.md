## Map

Renders a map of the world!
### Parameters

Configure the shape using the constructor arguments named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **mapResolution** | `PhpTui\Tui\Widget\Canvas\Shape\MapResolution` | Resolution of the map (enum low or high) |
| **color** | `PhpTui\Tui\Model\Color` | Color of the map |
### Example
The following code example:

{{% codeInclude file="/data/example/docs/shape/map.php" language="php" %}}

Should render as:

{{% terminal file="/data/example/docs/shape/map.snapshot" %}}