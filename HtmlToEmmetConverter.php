<?php


class HtmlToEmmetConverter
{

    private $htmlText;

    public function __construct($htmlText)
    {
        $this->htmlText = $htmlText;
    }

    public function convert()
    {

    }

    public function generateEmmet(TagModel $tagModel) : string {
        if(is_null($tagModel->getName())) {
            return "{".$tagModel->getContent()."}";
        }
        $emmet = $tagModel->getName();
        $attributes = $tagModel->getAttributes();

        foreach(array_keys($attributes) as $key) {
            if($key == "id") {
                $emmet .= "#".$attributes[$key];
            } else if ($key == "class") {

            }
        }

    }
}