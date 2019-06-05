<?php


class StringUtils
{
    /**
     *  Returns the inner text of a tag.
     * @param $tagName - tag name itself - without opening and closing symbol
     * @param $startingIndex - the first index after the opening of the tag in $string
     * @param $string - the whole string
     * @return string - the inner text of the $tag in $string
     */
    public static function getInnerTextOfTag($tagName, $startingIndex, &$string): string
    {
        if (is_null($tagName)) {
            $result = $string;
            $string = "";
            return $result;
        }

        $length = strlen($string);
        $sameInnerTags = 0;

        for ($index = $startingIndex; $index < $length; ++$index) {
            if ($string[$index] == '<') {
                if ($string[$index + 1] == '/') {
                    if (self::compareStrings($tagName, $string, 0, $index + 2, '>') == 0) {
                        --$sameInnerTags;
                    }
                } else {
                    if (self::compareStrings($tagName, $string, 0, $index + 1, '>') == 0) {
                        ++$sameInnerTags;
                    }
                }

                if ($sameInnerTags == -1) {
                    $result = self::subString($string, $startingIndex, $index);
                    $string = self::subString($string, $index + strlen($tagName) + 3, $length);
                    return $result;
                }
            }
        }
        return $string;
    }

    public static function compareStrings($left, $right, $startLeft, $startRight, $delimiter): int
    {
        $lengthLeft = strlen($left);
        $lengthRight = strlen($right);
        $currentLeft = "";
        $currentRight = "";
        $index = 0;

        while ($index + $startLeft < $lengthLeft && $index + $startRight < $lengthRight) {
            $currentLeft = $left[$index + $startLeft];
            $currentRight = $right[$index + $startRight];

            if ($currentLeft == $delimiter || $currentRight == $delimiter) {
                return 0;
            }

            if ($currentLeft != $currentRight) {
                if ((int)$currentLeft - (int)$currentRight > 0) {
                    return 1;
                }
                return -1;
            }

            ++$index;
        }

        if ($index + $startLeft < $lengthLeft && $left[$index + $startLeft] == $delimiter) {
            return 0;
        }

        if ($index + $startRight < $lengthRight && $right[$index + $startRight] == $delimiter) {
            return 0;
        }

        if ($currentLeft != $currentRight) {
            if ($currentLeft - $currentRight > 0) {
                return 1;
            }
            return -1;
        }
        return 0;
    }

    public static function subString($string, $startingIndex, $lastIndex): string
    {
        $subStr = "";

        for ($index = $startingIndex; $index < $lastIndex; ++$index) {
            $subStr .= $string[$index];
        }

        return $subStr;
    }

    public static function trimTagContent($tag): array
    {
        $length = strlen($tag);
        $items = array();
        $currentKey = "";
        $hasAddedNameTag = false;

        for ($index = 0; $index < $length; ++$index) {
            $current = $tag[$index];

            if (!self::isWhitespace($current) && $current != '=') {
                $currentKey .= $current;
            } else {
                while ($index < $length && self::isWhitespace($current)) {
                    $current = $tag[$index];
                    $index++;
                }

                if ($current == '=') {
                    $value = "";
                    $numberOfQuestionMarks = 0;

                    $index++;
                    $saveIndex = $index;
                    while ($numberOfQuestionMarks != 2 && $index < $length) {
                        $current = $tag[$index];
                        if ($current == '"' && $tag[$index - 1] != '\\') {
                            $numberOfQuestionMarks++;
                        } else if ($numberOfQuestionMarks == 1) {
                            $value .= $current;
                        }

                        $index++;
                    }

                    if ($value == "") {
                        $index = $saveIndex;
                        $current = $tag[$index];
                        while (self::isWhitespace($current) && $index < $length) {
                            ++$index;
                            $current = $tag[$index];
                        }
                        while (!self::isWhitespace($current) && $index < $length) {
                            $current = $tag[$index];
                            $value .= $current;
                            ++$index;
                        }
                    }

                    $items[$currentKey] = $value;
                } else if ($currentKey != "") {
                    if (!$hasAddedNameTag) {
                        $items[0] = $currentKey;
                    } else {
                        $items[$currentKey] = null;
                    }
                    $index -= 2;
                } else {
                    if (StringUtils::isWhitespace($current)) {
                        while ($index < $length && self::isWhitespace($current = $tag[$index])) {
                            $index++;
                        }
                    } else {
                        $index -= 2;
                    }
                }
                $currentKey = "";
            }

        }

        if ($currentKey != "") {
            $items[0] = $currentKey;
        }

        return $items;
    }

    public static function getFirstTagAndAttributes($string): string
    {
        $tag = "";
        $length = strlen($string);
        $foundTag = false;

        for ($index = 0; $index < $length && $string[$index] != '>'; ++$index) {
            if ($index + 1 < $length && $string[$index] == '/' && $string[$index + 1] == '>') {
                break;
            }

            if ($foundTag) {
                $tag .= $string[$index];
            }

            if ($string[$index] == '<') {
                $foundTag = true;
                if ($index + 1 < $length && $string[$index + 1] == '/') {
                    $index++;
                }
            }

        }

        return $tag;
    }

    public static function isTextOnly($string): bool
    {
        return strpos($string, '<') === false;
    }

    public static function squeezeString($string, $sequence)
    {
        $index = 0;
        $length = strlen($string);
        $lengthSequence = strlen($sequence);
        $hasAdded = false;

        while ($index < $length) {
            $result = self::compareStrings($string, $sequence, $index, 0, $lengthSequence);
            if ($result == 0 && !$hasAdded) {

            }
        }
    }

    public static function isWhitespace($char): bool
    {
        return ctype_space($char);
    }

    public static function hasContentBeforeTag($string): bool
    {
        for ($index = 0; $index < strlen($string) && $string[$index] != '<'; ++$index) {
            if (!self::isWhitespace($string[$index])) {
                return true;
            }
        }
        return false;
    }

    public static function whitespacesBeforeTag($string): int
    {
        $counter = 0;
        for ($index = 0; $index < strlen($string) && $string[$index] != '<'; ++$index) {
            if (!self::isWhitespace($string[$index])) {
                return $counter;
            }
            $counter++;
        }
        return $counter;
    }

    public static function removeFirstWhitespaces($string): string
    {
        for ($index = 0; $index < strlen($string); ++$index) {
            if (!self::isWhitespace($string[$index])) {
                return self::subString($string, $index, strlen($string));
            }
        }
        if (self::isWhitespace($string)) {
            return "";
        }

        return $string;
    }
}