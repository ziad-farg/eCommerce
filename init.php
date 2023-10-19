<?php
    // include connection of database
    include 'admin/connect.php';

    // routs
    $tmp    = 'include/templates/';
    $lang   = 'include/languages/';
    $func   = 'include/functions/';
    $css    = 'layout/css/';
    $js     = 'layout/js/';

    // include important files
    include $func . 'function.php';
    include $lang . 'english.php';
    include $tmp . 'header.php';
    include $tmp . 'uppernav.php';
    include $tmp . 'mainnav.php';
