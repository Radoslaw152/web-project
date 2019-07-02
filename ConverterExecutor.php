<?php
require_once 'HtmlToEmmetConverter.php';
require_once 'ErrorMessage.php';
require_once 'HTMLValidator.php';

class ConverterExecutor
{
    public $html2EmmetConverter;

    public function exec()
    {
        $requestBody = file_get_contents('php://input');
        $htmlText = json_decode($requestBody)->htmlText;

        $htmlValidator = new HTMLValidator();
        if($htmlValidator->validatePart($htmlText) == false) {
            ErrorMessage::sendMessage(403, "The current HTML text isn't valid.");
        }

        $this->html2EmmetConverter = new HtmlToEmmetConverter($htmlText);
        $result = $this->html2EmmetConverter->convert();

        echo "{ \"emmet\" : \"" . $result . "\" }";
    }
}