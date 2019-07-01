<?php

include_once 'RestAPI.php';

$restApi = new RestAPI();
$restApi->run();


//include_once 'HtmlToEmmetConverter.php';
//
//$string = "<html lang=\"en\">
//<head>
//    <meta charset=\"UTF-8\">
//    <title>Validation</title>
//    <script defer src=\"dom.js\"></script>
//</head>
//<body>
//<main>
//    <section>
//        <fieldset>
//            <legend>Registration</legend>
//            <form class=\"registration\">
//                <label for=\"username\">User name: </label>
//                <input type=\"text\" id=\"username\" name=\"username\"/>
//                <label for=\"pass\">Password: </label>
//                <input type=\"password\" id=\"pass\" name=\"password\"/>
//                <label for=\"passAgain\">Password again: </label>
//                <input type=\"password\" id=\"passAgain\" name=\"passwordAgain\"/>
//                <input type=\"button\" value=\"Register\" name=\"register\"/>
//            </form>
//        </fieldset>
//        <p id=\"exitMessage\"></p>
//    </section>
//</main>
//</body>
//</html>";
//$test = new HtmlToEmmetConverter($string);
//
//echo $test->convert();