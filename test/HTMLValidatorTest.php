<?php

require_once '..\HTMLValidator.php';

use PHPUnit\Framework\TestCase;

class HTMLValidatorTest extends TestCase
{
    public function testNA()
    {
        $text = "   <section>
        <label for=\"inputHtmlText\">Моля, въведете HTML текст: </label>
        <textarea id=\"inputHtmlText\" name=\"inputHtmlText\"></textarea>
        <button id=\"convertByHtmlText\" name=\"convertByHtmlText\">Convert to Emmet from HTML text</button>
        <label for=\"inputFile\">Моля, въведете файл: </label>
        <input id=\"inputFile\" accept=\"*/*\" type=\"file\" name=\"inputFile\">
        <button id=\"convertByFile\">Convert to Emmet from file</button>
        <p class=\"errorFiled\" id=\"exitMessage\"></p>
        <label for=\"emmetField\">Преобразуваният html в emmet: </label>
        <textarea id=\"emmetField\" readonly></textarea>
    </section>";

        $validator = new HTMLValidator($text);
        $result = $validator->validatePart($text);
        self::assertTrue($result);
    }
}
