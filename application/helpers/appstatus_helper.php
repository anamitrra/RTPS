<?php
defined("BASEPATH") or exit("No direct script access allowed");
if (!function_exists("getstatusname")) {
    function getstatusname($par)
    {
        switch ($par) {
            case 'submitted':
                $action = "Submitted";
                break;
            case 'S':
                $action = "Success";
                break;
            case 'P':
                $action = "Pending";
                break;
            case 'Y':
                $action = "Success";
                break;
            case 'N':
                $action = "Failed";
                break;
            case 'F':
                $action = "Failed";
                break;
            case 'A':
                $action = "Aborted ";
                break;
            case 'R':
                $action = "Rejected";
                break;
            case 'QS':
                $action = "Query Made";
                break;
            case 'QA':
                $action = "Query Answered";
                break;
            case 'FRS':
                $action = "Payment Pending";
                break;
            case 'DRAFT':
                $action = "DRAFT";
                break;
            case 'payment_initiated':
                $action = "Payment Pending";
                break;
            case 'PR':
                $action = "Payment Recieved";
                break;
            case 'D':
                $action = "Delivered";
                break;
            case 'AF':
                $action = "Application Forwarded";
                break;
            case 'UP':
                $action = "Under Processing";
                break;
            case 'AA':
                $action = "Application Approved";
                break;
            case 'CR':
                $action = "Call Back Request";
                break;
            default:
                $action = $par;
                break;
        } //End of switch
        return $action;
    } // End of getstatusname()
} // End of if statement
