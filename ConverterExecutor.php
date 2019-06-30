<?php
require_once 'HtmlToEmmetConverter.php';

class ConverterExecutor
{
    public $html2EmmetConverter;

    public function exec()
    {
        $requestBody = file_get_contents('php://input');
        $htmlObject = json_decode($requestBody);
        $this->html2EmmetConverter = new HtmlToEmmetConverter($htmlObject->htmlText);

        echo $this->html2EmmetConverter->convert();
    }
}