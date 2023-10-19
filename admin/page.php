<?php
$do = '';
$do = isset($_GET['do']) ? $_GET['do'] : 'Mange';

if ($do == 'Mange') {
    echo 'Welcom In Mange Page';
    echo '<a href="?do=Delete">Delete New Catogery</a>';
} elseif ($do == 'Add') {
    echo 'Welcom In Add Page';
} elseif ($do == 'Delete') {
    echo 'Welcom In Delete Page';
} else {
    echo 'Error';
}
