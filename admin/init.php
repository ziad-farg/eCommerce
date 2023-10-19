<?php

include 'connect.php';

// routes
$tmp = 'include/templates/'; // dire template [header,footer,navbar]
$lang = 'include/languages/'; //dir languages
$func = 'include/functions/'; //dir function
$css = 'layout/css/'; // dire files css
$js = 'layout/js/'; // dire files js


include $func . 'function.php';
include $lang . 'english.php'; //include languages
include $tmp . 'header.php'; //include header

// this is varaible used for hide navbar in pages
if (!isset($noNavbar)) {
    include $tmp . 'navbar.php'; //include navbar
}
