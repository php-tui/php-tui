## Paragraph

This widget has the ability to show and wrap text.
### Parameters

Configure the widget using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **style** | `PhpTui\Tui\Model\Style` |  |
| **wrap** | `PhpTui\Tui\Widget\Paragraph\Wrap\|null` |  |
| **text** | `PhpTui\Tui\Model\Widget\Text` |  |
| **scroll** | `array` |  |
| **alignment** | `PhpTui\Tui\Model\Widget\HorizontalAlignment` |  |
### Example
The following code example:

{{% codeInclude file="/data/example/docs/widget/paragraph.php" language="php" %}}

Should render as:

{{% terminal file="/data/example/docs/widget/paragraph.snapshot" %}}