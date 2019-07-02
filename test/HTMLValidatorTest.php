<?php

require_once '..\HTMLValidator.php';

use PHPUnit\Framework\TestCase;

class HTMLValidatorTest extends TestCase
{
    public function testNA()
    {
        $text = "rado<html";

        $validator = new HTMLValidator($text);
        $result = $validator->validatePart($text);
        self::assertTrue($result);
    }
}
