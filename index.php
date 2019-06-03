<?php

include_once 'StringUtils.php';
include_once 'TagModel.php';

$text = "text";

$model = new TagModel($text);
var_dump($model);

