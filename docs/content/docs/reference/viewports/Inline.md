---
title:  Inline
description: Viewport that is displayed _after_ the cursor's current position.
---
##  Inline

`PhpTui\Tui\Model\Viewport\Inline`

Viewport that is displayed _after_ the cursor's current position.


You can use this viewport in with `Display#insertBefore` in order to add content
before the viewport, which can be usedful for "logging" progress.

### Example

{{< details "Show code"  >}}
{{% codeInclude file="/data/example/docs/viewport/inline.php" language="php" %}}

{{< /details >}}
### Parameters

Configure the viewport using the builder methods named as follows:

| Name | Type | Description |
| --- | --- | --- |
| **height** | `int` | Height of the viewport |