<?php

ob_start();


if (isset($_SESSION['username'])) {
    
    include 'init.php';
    
    $title = '';
    
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {
        
    } elseif ($do = 'Add') {

    } elseif ($do = 'Edit') {

    } elseif ($do = 'Insert') {

    } elseif ($do = 'Update') {

    } elseif ($do = 'Delete') {

    }

    include $tmp . 'footer';

} else {

    header('LOCATION :index.php');

    exit();

}

ob_end_flush();