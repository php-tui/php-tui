---
title:  Buffer 
description: This widget provides access to the Buffer and allows you to mutate it directly in addition to being able to draw widgets.
---
##  Buffer 

`PhpTui\Tui\Extension\Core\Widget\BufferWidget`

This widget provides access to the Buffer and allows you to mutate it directly in addition to being able to draw widgets.


This is useful if you need to know the context upon which widgets are being
drawn (for example the absolute position of the containing area etc).

### Example

{{% terminal file="/data/example/docs/widget/bufferWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/bufferWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **widget** | `Closure(BufferContext $buffer): void` | The callback for writing to the buffer. |