<?php
include_once 'StringUtils.php';

class TagModel
{
    private $name;
    private $attributes;
    private $content;

    public function __construct($string)
    {
        $tagAttributes = StringUtils::getFirstTagAndAttributes($string);
        $this->attributes = StringUtils::trimTagContent($tagAttributes);
        if (sizeof($this->attributes) != 0) {
            $this->name = $this->attributes[0];
            unset($this->attributes[0]);
        } else {
            $this->name = null;
        }
        $this->content = StringUtils::getInnerTextOfTag($this->name, strlen($tagAttributes) + 2, $string);
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