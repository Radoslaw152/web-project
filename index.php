<?php

include_once 'HtmlToEmmetConverter.php';


$text = "<div>
    <header>
        <ul>
            <li><a href=\"\"></a></li>
            <li><a href=\"\"></a></li>
        </ul>
    </header>
    <footer>
        <p></p>
    </footer>
</div>";

$converter = new HtmlToEmmetConverter($text);

echo $converter->convert();

