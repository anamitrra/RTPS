<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if(!function_exists('getFinYear'))
{
    function getFinYear()
    {
        return date("m") >= 4 ? date("Y"). '-' . (date("Y")+1) : (date("Y") - 1). '-' . date("Y");
    }
}

if(!function_exists('firstDateFinYear'))
{
    function firstDateFinYear()
    {
        $cur_fin_year = date("m") >= 4 ? date("Y"): (date("Y") - 1);
        return "01/04/".$cur_fin_year;
    }
}

if(!function_exists('getFinLastYearDate'))
{
    function getFinLastYearDate()
    {
        $cur_fin_year = date("m") >= 4 ? (date("Y")+3): (date("Y")+2);
        return "31/03/".$cur_fin_year;
    }
}

if(!function_exists('getFinLastYearDateYMD'))
{
    function getFinLastYearDateYMD()
    {
        $cur_fin_year = date("m") >= 4 ? (date("Y")+3): (date("Y")+2);
        return $cur_fin_year."-03-31";
    }
}