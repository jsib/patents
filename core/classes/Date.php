<?php

namespace Core;
/**
 * Provide functions to format dates
 */
class Date
{
    /**
     * Convert date to str
     */
    public static function human($value)
    {
        //Get formatted date
        $date = date("d.m.Y", strtotime($value));
        
        if($date == '31.12.1969' || $date == '01.01.1970') {
            return '';
        } else {
            return $date;
        }
    }
}