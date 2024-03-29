<?php
require_once 'utils/StringUtils.php';

class TagModel
{
    private $name;
    private $attributes;
    private $content;

    public function __construct(&$string)
    {
        if (!StringUtils::hasContentBeforeTag($string)) {
            $string = StringUtils::removeFirstWhitespaces($string);
            $tagAttributes = StringUtils::getFirstTagAndAttributes($string);
            $this->attributes = StringUtils::trimTagContent($tagAttributes);

            if (sizeof($this->attributes) != 0) {
                $this->name = $this->attributes[0];
                unset($this->attributes[0]);
            } else {
                $this->name = null;
            }

            if (in_array($this->name, EmmetType::$ONE_TAG_ONLY)) {
                $numberOfBrackets = 1;
                if ($string[1] == '/') {
                    $numberOfBrackets++;
                }
                if($string[$numberOfBrackets + strlen($tagAttributes)] == '/') {
                    $numberOfBrackets++;
                }

                $string = StringUtils::subString($string,
                    strlen($tagAttributes) + $numberOfBrackets + 1,
                    strlen($string));
                $this->content = "";
            } else {
                $this->content = StringUtils::getInnerTextOfTag($this->name,
                    strlen($tagAttributes) + 2,
                    $string);
            }
        } else {
            $this->name = null;
            $this->attributes = array();
            $this->content = "";

            for ($index = 0; $index < strlen($string) && $string[$index] != '<'; ++$index) {
                $this->content .= $string[$index];
            }
            $string = StringUtils::subString($string, strlen($this->content), strlen($string));

        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}