<?php
require_once '..\HtmlToEmmetConverter.php';
use PHPUnit\Framework\TestCase;

class HtmlToEmmetConverterTest extends TestCase
{

    public static function test()
    {
        $htmlText = "<nav>
                        <ul>
                            <li></li>
                        </ul>
                    </nav>";
        $converter = new HtmlToEmmetConverter($htmlText);
        $result = $converter->convert();
        $expected = "nav>ul>li";
        self::assertEquals($expected, $result);

        $htmlText = "<div></div>
                    <p></p>
                    <blockquote></blockquote>";
        $converter->setHtmlText($htmlText);
        $result = $converter->convert();
        $expected = "div+p+blockquote";
        self::assertEquals($expected, $result);

        $htmlText = '<div>
                        <header>
                            <ul>
                                <li><a></a></li>
                                <li><a></a></li>
                            </ul>
                        </header>
                        <footer>
                            <p></p>
                        </footer>
                    </div>';
        $converter->setHtmlText($htmlText);
        $result = $converter->convert();
        $expected = "div>(header>ul>li*2>a)+footer>p";
        self::assertEquals($expected, $result);

        $htmlText = '<div>
                        <dl>
                            <dt></dt>
                            <dd></dd>
                            <dt></dt>
                            <dd></dd>
                            <dt></dt>
                            <dd></dd>
                        </dl>
                    </div>
                    <footer>
                        <p></p>
                    </footer>';
        $converter->setHtmlText($htmlText);
        $result = $converter->convert();
        $expected = "(div>dl>dt+dd+dt+dd+dt+dd)+footer>p";
        self::assertEquals($expected, $result);

        $htmlText = '<ul>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul>';
        $converter->setHtmlText($htmlText);
        $result = $converter->convert();
        $expected = "ul>li*5";
        self::assertEquals($expected, $result);

        $htmlText = '<div class="title" id="id"></div>
                    <form id="search" class="wide"></form>
                    <p class="class1 class2 class3"></p>';
        $converter->setHtmlText($htmlText);
        $result = $converter->convert();
        $expected = "div.title#id+form#search.wide+p.class1.class2.class3";
        self::assertEquals($expected, $result);

        $htmlText = '<p title="Hello world"></p>';
        $converter->setHtmlText($htmlText);
        $result = $converter->convert();
        $expected = "p[title=\"Hello world\"]";
        self::assertEquals($expected, $result);

        $htmlText = '<td rowspan="2" colspan="3" title=""></td>';
        $converter->setHtmlText($htmlText);
        $result = $converter->convert();
        $expected = "td[rowspan=2 colspan=3 title]";
        self::assertEquals($expected, $result);

        $htmlText = '<div a="value1" b="value2"></div>';
        $converter->setHtmlText($htmlText);
        $result = $converter->convert();
        $expected = "div[a=\"value1\" b=\"value2\"]";
        self::assertEquals($expected, $result);

        $htmlText = '<p>Click <a>here</a> to continue</p>';
        $converter->setHtmlText($htmlText);
        $result = $converter->convert();
        $expected = "p>{Click }+a{here}+{ to continue}";
        self::assertEquals($expected, $result);

        $htmlText = '<p>Click</p><meta><br>';
        $converter->setHtmlText($htmlText);
        $result = $converter->convert();
        $expected = "p{Click}+meta+br";
        self::assertEquals($expected, $result);
    }
}
