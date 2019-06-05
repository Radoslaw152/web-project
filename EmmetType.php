<?php


class EmmetType
{
    public static $ONE_TAG_ONLY = array(
        "meta",
        "br",
        "area",
        "base",
        "br",
        "col",
        "embed",
        "hr",
        "img",
        "input",
        "link",
        "param",
        "source",
        "track",
        "wbr"
    );

    public static $NOT_PARSE_CONTENT = array(
      "template",
        "script",
        "style",
        "textarea",
        "title"
    );

}