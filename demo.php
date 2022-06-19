<?php

use WordPressBlockTheme\TemplateParser;

require_once "src/simple_html_dom.php";
require_once "vendor/autoload.php";

$parser = new TemplateParser('sample.html');
$parser->setContent('#content');
$parser->replace('h1', 'Sample generated content');
$parser->generate('generated.html');
