<?php
defined("BASEPATH") OR exit("No direct script access allowed");
if(!function_exists("log_response")){
    function log_response($app_ref_no,$data,$collection="response_logs"){
        if(empty($data) || empty($app_ref_no)){
            return false;
        }
        $CI = &get_instance();
        $_arr=array(
            'app_ref_no'=>$app_ref_no,
            'response'=>$data,
            'createdDtm'=>new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)
        );
       return $CI->mongo_db->insert($collection,$_arr);
    } 
} // End of if statement


if(!function_exists("edistrict_log_response")){
    function edistrict_log_response($app_ref_no,$data,$collection="edistrict_response_logs"){
        if(empty($data) || empty($app_ref_no)){
            return false;
        }
        $CI = &get_instance();
        $_arr=array(
            'app_ref_no'=>$app_ref_no,
            'response'=>$data,
            'createdDtm'=>new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)
        );
       return $CI->mongo_db->insert($collection,$_arr);
    } 
} // End of if statement

if(!function_exists("edistrict_mi_log_response")){
    function edistrict_mi_log_response($app_ref_no,$data,$collection="edistrict_mi_response_logs"){
        if(empty($data) || empty($app_ref_no)){
            return false;
        }
        $CI = &get_instance();
        $_arr=array(
            'app_ref_no'=>$app_ref_no,
            'response'=>$data,
            'createdDtm'=>new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)
        );
       return $CI->mongo_db->insert($collection,$_arr);
    } 
} 