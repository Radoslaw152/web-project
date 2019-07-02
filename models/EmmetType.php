<?php


class EmmetType
{
    public static $ONE_TAG_ONLY = array(
        "meta",
        "br",
        "area",
        "base",
        "col",
        "embed",
        "hr",
        "img",
        "input",
        "link",
        "param",
        "source",
        "track",
        "wbr",
        "!DOCTYPE"
    );

    public static $NOT_PARSE_CONTENT = array(
      "template",
        "script",
        "style",
        "textarea",
        "title"
    );

}