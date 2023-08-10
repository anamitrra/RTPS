<?php
defined("BASEPATH") OR exit("No direct script access allowed");
if(!function_exists("getappstatus")){
    function getappstatus($par){
        switch ($par) {
            case 'DRAFT':
                $action = "IN DRAFT MODE";
                break;
            case 'SUBMITTED':
                $action = "APPLICATION SUBMITTED";
                break;
            case 'PAYMENT_INITIATED':
                $action = "PAYMENT INITIATED";
                break;
            case 'PAYMENT_FAILED':
                $action = "PAYMENT FAILED";
                break;
            case 'PAYMENT_COMPLETED':
                $action = "PAYMENT COMPLETED";
                break;
            case 'UNDER_PROCESSING':
                $action = "UNDER PROCESSING";
                break;
            case 'QUERY_ARISE':
                $action = "QUERY MADE";
                break;
            case 'QUERY_SUBMITTED':
                $action = "QUERY REPLIED";
                break;        
            case 'DELIVERED':
                $action = "APPLICATION DELIVERED";
                break;
            case 'REJECTED':
                $action = "APPLICATION REJECTED";
                break;
            default:
                $action =$par;
                break;
        }//End of switch
        return $action;
    } // End of getappstatus()
} // End of if statement