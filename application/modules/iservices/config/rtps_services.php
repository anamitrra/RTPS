<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

$config['encryption_key']="1234567890123456";
//for sarathi 
$config['agId']="rtps";
$config['agencyPassword']="feda47430fbeebc7";
$config['agencyKey']="bbd8409829c6c214";

//staging

// $config['vahan_retry_url']="https://staging.parivahan.gov.in/vahanservice/vahan/ui/edistrict/eDistrictEApplicatonHome.xhtml";
// // $config['vahan_status_url']="https://vahan.parivahan.gov.in/knowapplservice/knowappl/fetchyourApplDetails/";
// $config['vahan_status_url']="https://staging.parivahan.gov.in/vahanservice/vahan/ui/edistrict/form_Know_Appl_Status.xhtml";

// $config['egras_url']="https://uatgras.assam.gov.in/challan/views/frmgrnfordept.php";
// $config['egras_grn_cin_url']="https://uatgras.assam.gov.in/challan/models/frmgetgrn.php";



// $config['getSarApplNoUrl']="http://sarathicov.nic.in:8080/sarathiservice/rsServices/CscApplDetWsevice/getSarApplNo";
// $config['getSarApplDetUrl']="http://sarathicov.nic.in:8080/sarathiservice/rsServices/CscApplDetWsevice/getSarApplDet";

// $config['getSarApplNoUrl']="http://sarathicov.nic.in:8080/sarathiservice/rsServices/CscApplDetWsevice/getSarApplNo";
// $config['getSarApplDetUrl']="http://sarathicov.nic.in:8080/sarathiservice/rsServices/CscApplDetWsevice/getSarApplDet";
// $config['getApplStatusDetUrl']="http://sarathicov.nic.in:8080/sarathiservice/rsServices/applicationStatusDetsService/getApplStatusDet";
// $config['SarPayRequest']="http://sarathicov.nic.in:8080/payments/rsServices/SarPayVerify/getSarPayVerify";
// $config['getApplStatusUrl']="http://sarathicov.nic.in:8080/sarathiWS/rsServices/applicationStatus/applStatus";
// $config['csc_account']="ARI68751";
// $config['noc_push_payment_status_url']="https://ilrms.nic.in/noctest/index.php/usercontrol/paymentupdate";
// $config['basundhara_status_url']="https://basundhara.assam.gov.in/rtpsdemo/welcome/trackApplicationView";
//production

$config['vahan_retry_url']="https://vahan.parivahan.gov.in/vahanservice/vahan/ui/edistrict/eDistrictEApplicatonHome.xhtml";
$config['vahan_status_url']="https://vahan.parivahan.gov.in/vahanservice/vahan/ui/edistrict/form_Know_Appl_Status.xhtml";

$config['egras_url']="https://assamegras.gov.in/challan/views/frmgrnfordept.php";
$config['egras_grn_cin_url']="https://assamegras.gov.in/challan/models/frmgetgrn.php";


$config['getSarApplNoUrl']="https://sarathi.parivahan.gov.in/sarathiservice/rsServices/CscApplDetWsevice/getSarApplNo";
$config['getSarApplDetUrl']="https://sarathi.parivahan.gov.in/sarathiservice/rsServices/CscApplDetWsevice/getSarApplDet";
$config['getApplStatusDetUrl']="https://sarathi.parivahan.gov.in/sarathiservice/rsServices/applicationStatusDetsService/getApplStatusDet";
$config['SarPayRequest']="https://sarathi.parivahan.gov.in/paymentscov/rsServices/SarPayVerify/getSarPayVerify";

$config['getApplStatusUrl']="https://sarathi.parivahan.gov.in/sarathiWS/rsServices/applicationStatus/applStatus";



$config['encrypt_url']="http://127.0.0.1:8080/transport-encryption-1.0/application/encrypt/";
$config['decrypt_url']="http://127.0.0.1:8080/transport-encryption-1.0/application/decrypt/";



$config['basundhara_push_payment_status_url']="https://basundhara.assam.gov.in/rtps/Epayment/updatePayment";
$config['basundhara_status_url']="https://basundhara.assam.gov.in/rtps/welcome/trackApplicationView";
$config['noc_push_payment_status_url']="https://ilrms.nic.in/noc/index.php/usercontrol/paymentupdate";

$config['csc_account']="ARI71580";


//fee
$config['service_charge']="30";
$config['printing_charge']="10";
$config['scanning_charge']="5";

$config['rtps_account']="ARI64576";
$config['rtps_convenience_fee']="10";


//Serviceplus Integration
$config['serviceplus_encryption_api']="http://localhost:8080/EncryptionModule/api/encrypt/";
$config['eodbAuthenticationApi']="https://eodb.assam.gov.in/esconfigure/authorisation";
$config['rtpsAuthenticationApi']="https://rtps.assam.gov.in/configure/authorisation";
$config['get_tiny_url']="https://rtps.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/get_tiny_url";
$config['get_track_data']="https://rtps.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/get_track_data";

//Unifide login of Serviceplus applications for iservices dashboard
$config['splusGetPendingApplications']="https://rtps.assam.statedatacenter.in/tools/rtps_id_labels/src/api/external_apis.php/get_sp_pending_applications";
$config['splusGetDeliveredApplications']="https://rtps.assam.statedatacenter.in/tools/rtps_id_labels/src/api/external_apis.php/get_sp_delivered_applications";
$config['splusTrackURL']="https://rtps.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/get_track_data";

// $config['splusGetPendingApplications']="http://localhost/rtps_id_labels/src/api/external_apis.php/get_sp_pending_applications";
// $config['splusGetDeliveredApplications']="http://localhost/rtps_id_labels/src/api/external_apis.php/get_sp_delivered_applications";
