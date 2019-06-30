<?php

class TagModelFactory
{
    public static function createTagModel($string) : TagModel 
    {

        if (!StringUtils::hasContentBeforeTag($string)) {
            $string = StringUtils::removeFirstWhitespaces($string);
            $tagAttributes = StringUtils::getFirstTagAndAttributes($string);
            $attributes = StringUtils::trimTagContent($tagAttributes);
            if (sizeof($attributes) != 0) {
                $name = $attributes[0];
                unset($attributes[0]);
            } else {
                $name = null;
            }
            if(in_array($name, EmmetType::$ONE_TAG_ONLY)) {
                $string = StringUtils::subString($string, strlen($tagAttributes) + 2)
            } else {
                $content = StringUtils::getInnerTextOfTag($name,
                    strlen($tagAttributes) + 2,
                    $string);
                $string = StringUtils::removeFirstWhitespaces($string);
            }
        } else {
            $name = null;
            $attributes = array();
            $content = "";

            for ($index = 0; $index < strlen($string) && $string[$index] != '<'; ++$index) {
                $content .= $string[$index];
            }
            $string = StringUtils::subString($string, strlen($content), strlen($string));

        }

    }

    private static function 
}