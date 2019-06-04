<?php
include_once 'StringUtils.php';
include_once 'TagModel.php';

class HtmlToEmmetConverter
{

    private $htmlText;

    public function __construct($htmlText)
    {
        $this->htmlText = $htmlText;
    }

    public function convert(): string
    {
        $parent = new TagModel($this->htmlText);
        return $this->generateEmmet($parent);
    }

    private function generateEmmet(TagModel $tagModel): string
    {
        if (is_null($tagModel->getName())) {
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
                $trimContent = "";
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
                if (!is_null($attributes[$key])) {
                    $customAttributes .= "=" . $attributes[$key];
                }
            }
        }
        if ($customAttributes != "") {
            $emmet .= "[" . $customAttributes . "]";
        }


        $content = $tagModel->getContent();
        $innerEmmets = array();

        while ($content != "") {
            $child = new TagModel($content);
            $childEmmet = $this->generateEmmet($child);

            array_push($innerEmmets, $childEmmet);
        }

        if (sizeof($innerEmmets) > 0) {
            $emmet .= ">";
        }

        for ($index = 0; $index < sizeof($innerEmmets); ++$index) {
            $counter = 0;
            $currentEmmet = $innerEmmets[$index];

            while ($index < sizeof($innerEmmets) && $currentEmmet == $innerEmmets[$index]) {
                ++$index;
                ++$counter;
            }

            if ($counter > 1) {
                $currentEmmet .= "*" . $counter;
            }
            --$index;

            if ($index + 1 < sizeof($innerEmmets)) {
                if($this->hasMoreThanOneChild($currentEmmet)) {
                    $currentEmmet = "(".$currentEmmet.")";
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

            if($current == '(') {
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