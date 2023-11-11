---
weight: 10
---

# Getting Started

> NOTE: This won't work until the package has been published!

Let's render a map _of the world_.

First create a new project:

```shell
mkdir tui-demo
cd tui-demo
```

and require the `php-tui` package:

```
composer require php-tui/php-tui
```

Now create the file `map.php` with the following content:

{{% codeInclude file="/data/example/docs/getting-started/map.php" language="php" %}}

Execute the script:

```
$ php map.php
```

And you should see the following:

{{% terminal file="/data/example/docs/getting-started/map.html" %}}
