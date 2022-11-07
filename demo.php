<?php

use WordPressBlockTheme\TemplateParser;

require_once "src/simple_html_dom.php";
require_once "vendor/autoload.php";

$parser = new TemplateParser('html/recept.html');
//$parser = new TemplateParser('html/image.html');
$parser->setContent('#receptbody');
$parser->generate('generated.html');

// TODO
//- ADD BODY CLASS
//- FIX BR IN P TAGS
//- ENQUEUE STYLES
//- COPY IMAGE FILES
//- GENERATE HEADER
//- GENERATE FOOTER
//- setHeader(selector) -> generate to file
// BR TO SPACER BRBR TO SPACER
