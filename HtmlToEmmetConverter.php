<?php
include_once 'StringUtils.php';
include_once 'TagModel.php';
include_once 'EmmetType.php';

class HtmlToEmmetConverter
{

    private $htmlText;

    private $nullEmmet = "NULL";

    public function __construct($htmlText)
    {
        $this->htmlText = $htmlText;
    }

    public function convert(): string
    {
        $children = array();

        while ($this->htmlText != "") {
            $child = new TagModel($this->htmlText);
            $emmetChild = $this->generateEmmet($child);
            array_push($children, $emmetChild);
        }

        return $this->combineChildren($children);
    }

    public function setHtmlText(string $htmlText): void
    {
        $this->htmlText = $htmlText;
    }

    private function generateEmmet(TagModel $tagModel): string
    {
        if (is_null($tagModel->getName())) {
            if ($tagModel->getContent() == "") {
                return $this->nullEmmet;
            }
            return "{" . $tagModel->getContent() . "}";
        }


        $emmet = $tagModel->getName();
        $attributes = $tagModel->getAttributes();
        $customAttributes = "";

        foreach (array_keys($attributes) as $key) {
            if ($key == "id") {
                $emmet .= "#" . $attributes[$key];
            } else if ($key == "class") {
                $tagContent = $attributes[$key];
                $trimContent = ".";
                $hasAddedDot = false;
                $removeStartingSpaces = false;

                for ($index = 0; $index < strlen($tagContent); ++$index) {
                    $current = $tagContent[$index];
                    if (!(StringUtils::isWhitespace($current) && $hasAddedDot)) {
                        if (StringUtils::isWhitespace($current) && $removeStartingSpaces) {
                            $trimContent .= '.';
                            $hasAddedDot = true;
                        } else if ($current != ' ') {
                            $trimContent .= $current;
                            $hasAddedDot = false;
                            $removeStartingSpaces = true;
                        }
                    }
                }

                $emmet .= $trimContent;
            } else {
                if ($customAttributes != "") {
                    $customAttributes .= ' ';
                }

                $customAttributes .= $key;

                if (!is_null($attributes[$key]) && $attributes[$key] != "\"\"") {
                    $value = $attributes[$key];
                    $value = "\"" . $value . "\"";
                    $customAttributes .= "=" . $value;
                }
            }
        }
        if ($customAttributes != "") {
            $emmet .= "[" . $customAttributes . "]";
        }


        $content = $tagModel->getContent();

        if (in_array($content, EmmetType::$NOT_PARSE_CONTENT)) {
            if ($content == "") {
                return $this->nullEmmet;
            }
            return ">{" . $content . "}";
        }

        $innerEmmets = array();
        $hasContent = false;

        while ($content != "") {
            $child = new TagModel($content);
            $childEmmet = $this->generateEmmet($child);
            if ($childEmmet != $this->nullEmmet) {
                array_push($innerEmmets, $childEmmet);
            }
            if(preg_match("/^{.*/",$childEmmet)) {
                $hasContent = true;
            }
        }

        if (sizeof($innerEmmets) > 0 &&
            !(sizeof($innerEmmets) == 1 && $hasContent)) {
            $emmet .= ">";
        }


        return $emmet . $this->combineChildren($innerEmmets);
    }

    private function combineChildren($innerEmmets): string
    {
        $emmet = "";


        for ($index = 0; $index < sizeof($innerEmmets); ++$index) {
            $counter = 0;
            $currentEmmet = $innerEmmets[$index];

            while ($index < sizeof($innerEmmets) && $currentEmmet == $innerEmmets[$index]) {
                ++$index;
                ++$counter;
            }

            if ($counter > 1) {
                $currentEmmet = StringUtils::addAfterFirstMatchOrAtEnd($currentEmmet, ">", "*" . $counter);
            }
            --$index;

            if ($index + 1 < sizeof($innerEmmets)) {
                if ($this->hasMoreThanOneChild($currentEmmet)) {
                    $currentEmmet = "(" . $currentEmmet . ")";
                }
                $currentEmmet .= "+";
            }


            $emmet .= $currentEmmet;
        }

        return $emmet;
    }

    private function hasMoreThanOneChild($emmet): bool
    {
        $nestedLevel = 0;
        for ($index = 0; $index < strlen($emmet); ++$index) {
            $current = $emmet[$index];

            if ($current == '(') {
                $nestedLevel++;
            } else if ($current == ')') {
                $nestedLevel--;
            } else if ($current == '>' && $nestedLevel == 0) {
                return true;
            }
        }
        return false;
    }
}