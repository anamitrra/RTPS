<?php
defined("BASEPATH") or exit("No direct script access allowed");
if (!function_exists("digilockerservice_list")) {
    function digilockerservice_list($par)
    {
        switch ($par) {
            case 'TENANT':
                $action = "SETTLEMENT TENANT";
                break;
            case 'AP TRNASFER':
                $action = "SETTLEMENT AP TRANSFER";
                break;
            case 'Mutation Order':
                $action = "Issuance of Certified Copy of Mutation Order";
                break;
            case 'Registered Deed':
                $action = "Certified Copy of Registered Deed";
                break;
            case 'Delayed Birth Registration':
                $action = "Permission for Delayed Birth Registration";
                break;
            case 'Delayed Death Registration':
                $action = "Permission for Delayed Death Registration";
                break;
            case 'Assessment of Holding':
                $action = "Assessment of Holding";
                break;
            case 'Holding Mutation':
                $action = "Holding Mutation";
                break;

            default:
                $action = $par;
                break;
        } //End of switch
        return $action;
    } // End of getstatusname()
} // End of if statement