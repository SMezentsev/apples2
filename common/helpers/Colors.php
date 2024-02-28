<?php

namespace common\helpers;

class Colors {

    /**
     * @return string
     */
    public static function getRandomColor()
    {

        return str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}