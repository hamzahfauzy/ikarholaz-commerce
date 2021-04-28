<?php

namespace App\Models;

class Price
{
    private static $price_list = [
        1 => 750000,
        2 => 650000,
        3 => 550000,
        4 => 450000,
        5 => 350000,
        6 => 250000,
        7 => 200000,
        8 => 100000
    ];
    static function get($digit)
    {
        return self::$price_list[$digit];
    }
}
