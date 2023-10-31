## RawWidget

This widget allows you to write directly to the buffer through a closure.
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **widget** | `Closure(Buffer $buffer): void` | The callback for writing to the buffer. |
### Example
The following code example:

{{% codeInclude file="/data/example/docs/widget/rawWidget.php" language="php" %}}

Should render as:

{{% terminal file="/data/example/docs/widget/rawWidget.snapshot" %}}