<?php

include_once 'HTMLParser.php';

$document = new HTMLParser('parse_me.html');

$document->print_tree();