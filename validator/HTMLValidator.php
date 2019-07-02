<?php

require_once 'utils/StringUtils.php';
require_once 'models/EmmetType.php';

class HTMLValidator
{

    public function validatePart(string $part): bool
    {
        $part = StringUtils::removeFirstWhitespaces($part);
        $tags = array();
        $part = StringUtils::goToFirstBracket($part);
        while ($part != "") {
            $tag = $this->isValidTag($part);
            if (is_null($tag)) {
                return false;
            }

            $nameTag = self::checkInnerTag($tag);
            if (is_null($nameTag)) {
                return false;
            }

            $toAdd = 1;
            if (preg_match("/\/.*/", $nameTag) || preg_match("/.*\//", $nameTag)) {
                $toAdd = -1;
            }
            $modifiedNameTag = "";
            for ($index = 0; $index < strlen($nameTag); ++$index) {
                if($nameTag[$index] != '/') {
                    $modifiedNameTag .= $nameTag[$index];
                }
            }

            if (key_exists($modifiedNameTag, $tags)) {
                $tags[$modifiedNameTag] += $toAdd;
            } else  {
                if ($toAdd == -1 && !in_array($tag, EmmetType::$ONE_TAG_ONLY)) {
                    return false;
                }
                $tags[$modifiedNameTag] = 1;
            }
            $part = StringUtils::goToFirstBracket($part);
        }

        foreach (array_keys($tags) as $tag) {
            $times = $tags[$tag];
            if ($times != 0 && !in_array($tag, EmmetType::$ONE_TAG_ONLY)) {
                return false;
            }
        }
        return true;
    }

    private
    function isValidTag(string &$part)
    {
        $changed = "";
        $brackets = 0;
        $length = strlen($part);
        $insideQuotes = false;
        $hasAddedSingleSpace = false;
        $hasFoundOpenningBracket = false;
        $index = 0;

        for (; $index < $length; ++$index) {
            if ($brackets < 0 || $brackets > 1) {
                return null;
            } else if ($brackets == 0 && $hasFoundOpenningBracket) {
                break;
            }

            $current = $part[$index];

            if ($current == '<') {
                $brackets++;
                $hasFoundOpenningBracket = true;
            } else if ($current == '>') {
                $brackets--;
            } else {
                if ($current == '"') {
                    $insideQuotes = !$insideQuotes;
                }
                if ($current == ' ' && !$hasAddedSingleSpace && !$insideQuotes) {
                    $changed .= $current;

                    while ($current == ' ') {
                        $index++;
                        $current = $part[$index];
                    }
                    $index--;
                } else {
                    $changed .= $current;
                }
            }
        }

        if ($brackets == 1) {
            return null;
        }

        $part = StringUtils::subString($part, $index, $length);
        return $changed;
    }

    private
    static function checkInnerTag($tag)
    {
        $elements = StringUtils::trimTagContent($tag);
        if (sizeof($elements) == 0 || $elements[0] == "") {
            return null;
        }
        return $elements[0];
    }

}
