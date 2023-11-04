---
title: RawWidget
description: This widget allows you to write directly to the buffer through a closure.
---
## RawWidget

This widget allows you to write directly to the buffer through a closure.
{{% terminal file="/data/example/docs/widget/rawWidget.html" %}}
{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/widget/rawWidget.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **widget** | `Closure(Buffer $buffer): void` | The callback for writing to the buffer. |