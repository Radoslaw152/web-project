<?php

include_once 'HtmlToEmmetConverter.php';


$text = "
<html lang=3>
<head>
    <title>Title</title>
</head>
<body>

</body>
</html>";

$converter = new HtmlToEmmetConverter($text);

echo $converter->convert();

