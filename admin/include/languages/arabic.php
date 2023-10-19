<?php

function langs($pharse)
{
    static $arr = [
        'Massage' => 'مرحباً',
        'Admin' => 'باللغة العربية',
    ];
    return $arr[$pharse];
}
