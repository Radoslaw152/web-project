<?php

include_once 'HtmlToEmmetConverter.php';


$text = "<table>
    <tr class=\"row\">
        <td class=\"col\"></td>
    </tr>
</table>";

$converter = new HtmlToEmmetConverter($text);

echo $converter->convert();

