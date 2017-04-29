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
    public static function human($date_input, $replacement = '')
    {
        //Get formatted date
        $date = date( "d.m.Y", strtotime($date_input) );
        
        //Date is empty, apply replacement
        if( $date == '31.12.1969' || $date == '01.01.1970' ) {
            return $replacement;
        }
        
        return $date;
    }
}