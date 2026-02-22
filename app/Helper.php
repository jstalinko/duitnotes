<?php

namespace App;

class Helper
{
    public static function normalizePhone($phone)
    {
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('-', '', $phone);
        $phone = str_replace('+', '', $phone);
        $phone = str_replace('(', '', $phone);
        $phone = str_replace(')', '', $phone);
        // change ^08 -> 628
        if (preg_match('/^08/', $phone)) {
            $phone = '62' . substr($phone, 1);
        }
        return $phone;
    }
}
