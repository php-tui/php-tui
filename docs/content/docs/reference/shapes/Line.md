## Line

Draw a straight line from one point to another.
{{% terminal file="/data/example/docs/shape/line.snapshot" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/shape/line.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the shape using the constructor arguments named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **point1** | `PhpTui\Tui\Model\Widget\FloatPosition` | Draw from this point |
| **point2** | `PhpTui\Tui\Model\Widget\FloatPosition` | Draw to this point |
| **color** | `PhpTui\Tui\Model\Color` | Color of the line |