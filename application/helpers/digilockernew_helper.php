<?php
defined("BASEPATH") or exit("No direct script access allowed");
if (!function_exists("digilockernew")) {
    function digilockernew($doc_type)
    {
        switch ($doc_type) {
            case 'EICRD':
                $data = array(
                    "service_name" => array('$in' => ['Registration of employment seeker in Employment Exchange', 'Registration of employment seeker in Employment Exchange for AADHAAR Based']),
                    "api_key" => "EICRD",
                    "docType" => "EICRD",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'CECPY':
                $data = array(
                    "api_key" => "CCMO",
                    "docType" => "CECPY",
                    "UDFs" => array("service_data.appl_ref_no" => "param2"),
                    "db" => "iservices",
                    "collection" => "sp_applications",
                    "useAPI" => false
                );
                break;
            case 'LRCER':
                $data = array(
                    "api_key" => "LRCER",
                    "docType" => "LRCER",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param2"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'LVCER':
                $data = array(
                    "api_key" => "LVCER",
                    "docType" => "LVCER",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'AOCER':
                $data = array(
                    "api_key" => "AOCER",
                    "docType" => "AOCER",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'NKCER':
                $data = array(
                    "api_key" => "NKCER",
                    "docType" => "NKCER",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'TDLCS':
                $data = array(
                    "api_key" => "TDLCS",
                    "docType" => "TDLCS",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'LSTMT':
                $data = array(
                    "api_key" => "LSTMT",
                    "docType" => "LSTMT",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'INCER':
                $data = array(
                    "api_key" => "INCER",
                    "docType" => "INCER",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'SSMGR':
                $data = array(
                    "api_key" => "SSMGR",
                    "docType" => "SSMGR",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'HSMGR':
                $data = array(
                    "api_key" => "HSMGR",
                    "docType" => "HSMGR",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'RCBCW':
                $data = array(
                    "api_key" => "RCBCW",
                    "docType" => "RCBCW",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'LCCER':
                $data = array(
                    "api_key" => "LCCER",
                    "docType" => "LCCER",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'NLCER':
                $data = array(
                    "api_key" => "NLCER",
                    "docType" => "NLCER",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'SICRD':
                $data = array(
                    "api_key" => "SICRD",
                    "docType" => "SICRD",
                    "UDFs" => array("service_data.appl_ref_no" => "param1"),
                    "db" => "iservices",
                    "collection" => "sp_applications",
                    "useAPI" => false
                );
                break;
            case 'NNCER':
                $data = array(
                    "api_key" => "NNCER",
                    "docType" => "NNCER",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'RMCER':
                $data = array(
                    "api_key" => "RMCER",
                    "docType" => "RMCER",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => true,
                    "api_parameters" => array("application_ref_no" => "param1"),
                    "api_url" => "http://10.177.0.33:800/rtps_all_api/mrgapi.php?"
                );
                break;
            case 'STDOC':
                $data = array(
                    "service" => "",
                    "api_key" => "STDOC",
                    "docType" => "STDOC",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "useAPI" => true,
                    "api_parameters" => array("application_no" => "param1", "name" => "FullName", "doc_type" => "param2"),
                    "api_url" => "https://basundhara.assam.gov.in/rtpsmb2demo/Api/getDeliveryDoc"
                );
                break;
            case 'STDOC1':
                $data = array(
                    "service" => "",
                    "api_key" => "STDOC1",
                    "docType" => "STDOC1",
                    "UDFs" => array("app_ref_no" => "param1"),
                    "db" => "iservices",
                    "collection" => "intermediate_ids",
                    "useAPI" => true,
                    "api_parameters" => array("application_no" => "param1", "doc_type" => "param2"),
                    // "api_url" => "https://basundhara.assam.gov.in/rtpsmb/getDeliveryDoc"
                    "api_url" => "https://basundhara.assam.gov.in/rtpsmb/getDeliveryDocTest"
                );
                break;
            case 'ROR1B':
                $data = array(
                    "service" => "",
                    "api_key" => "RTPS",
                    "docType" => "ROR1B",
                    "UDFs" => array("app_ref_no" => "param1"),
                    "db" => "iservices",
                    "collection" => "intermediate_ids",
                    "useAPI" => true,
                    "api_parameters" => array("application_no" => "param1"),
                    // "api_url" => "https://basundhara.assam.gov.in/rtpsmb/getDeliveryDoc"
                    "api_url" => "https://basundhara.assam.gov.in/rtpsmb/getDeliveryDocTest"
                );
                break;
            case 'CTCER':
                $data = array(
                    "api_key" => "CTCER",
                    "docType" => "CTCER",
                    "UDFs" => array("initiated_data.appl_ref_no" => "param1"),
                    "db" => "mis",
                    "collection" => "applications",
                    "useAPI" => false
                );
                break;
            case 'ABCD':
                $data = array(
                    "api_key" => "ABCD",
                    "docType" => "ABCD",
                    "UDFs" => array("name" => "FullName", "service" => "param1", "appl_ref_no" => "param2"),
                    "useAPI" => true
                );
                break;
            default:
                $data = $doc_type;
                break;
        } //End of switch
        return $data;
    } // End of digilocker_new()
} // End of if statement