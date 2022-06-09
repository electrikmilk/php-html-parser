# PHP HTML Parser
Basic HTML parser in PHP.

<hr>

This parses HTML like this:

```html
<!doctype html>
<html>
<head>
	<title>Title</title>
</head>
<body>
	<h1>Heading</h1>
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elit ut aliquam purus sit amet luctus.</p>
</body>
</html>
```

Into an array like this for use in PHP code.

```console
Array
(
    [0] => Array
        (
            [html] => Array
                (
                )

        )

    [1] => Array
        (
            [head] => Array
                (
                )

        )

    [2] => Array
        (
            [title] => Array
                (
                    [inner] => Title
                )

        )

    [3] => Array
        (
            [body] => Array
                (
                )

        )

    [4] => Array
        (
            [h1] => Array
                (
                    [inner] => Heading
                )

        )

    [5] => Array
        (
            [p] => Array
                (
                    [inner] => Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Elit ut aliquam purus sit amet luctus.
                )

        )

)
```
