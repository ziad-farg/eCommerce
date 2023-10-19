<?php

function langs($pharse)
{
    static $arr = [
        //dashbord page
        'HOME-ADMIN' => 'Home',
        'CATOGERIES' => 'Catogeries',
        'ITEMS' => 'Items',
        'MEMBERS' => 'Members',
        'COMMENT' => 'Comment',
        'STATISTICS' => 'Statistics',
        'LOGS' => 'Logs',
    ];
    return $arr[$pharse];
}
