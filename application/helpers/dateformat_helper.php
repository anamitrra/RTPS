<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if(!function_exists('dateFormat'))
{
    function dateFormat($format='Y-m-d', $givenDate=null)
    {
        return date($format, strtotime($givenDate));
    }
}

if(!function_exists('getISOTimestamp'))
{
    function getISOTimestamp()
    {
        $curr_timestamp = date("Y-m-d").'T'.date("H:i:s").'.'.date("v").'Z';
        $mongoDateObject = new MongoDB\BSON\UTCDateTime(strtotime($curr_timestamp) * 1000);
        return $mongoDateObject;
    }
}

if(!function_exists('getDateFormat'))
{
    function getDateFormat($timestamp, $format = 'd-m-Y g:i a')
    {
        $ci = get_instance();
        return date($format, strtotime($ci->mongo_db->getDateTimeWithoutTZ($timestamp)));
    }
}

if(!function_exists('getCurrentTimestamp'))
{
    function getCurrentTimestamp()
    {
        $curr_timestamp = date("Y-m-d").'T'.date("H:i:s").'.'.date("v").'Z';
        return $curr_timestamp;
    }
}