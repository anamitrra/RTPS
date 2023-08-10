<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
//staging
$config['encryption_key']="1234567890123456";
// $config['vahan_retry_url']="https://staging.parivahan.gov.in/vahanserviceprod/vahan/ui/eapplication/form_eAppCommonHome.xhtml";
$config['vahan_retry_url']="https://staging.parivahan.gov.in/vahanservice/vahan/ui/edistrict/eDistrictEApplicatonHome.xhtml";
// $config['vahan_retry_url']="https://vahan.parivahan.gov.in/vahanservice/vahan/ui/edistrict/form_eDistrict_eAppCommonHome.xhtml";


// $config['encrypt_url']="http://localhost:9090/transport-encryption-1.0/application/encrypt/";
// $config['decrypt_url']="http://localhost:9090/transport-encryption-1.0/application/decrypt/";
$config['vahan_status_url']="https://staging.parivahan.gov.in/knowapplservice/knowappl/fetchyourApplDetails/?";
// $config['vahan_status_url']="https://vahan.parivahan.gov.in/knowapplservice/knowappl/fetchyourApplDetails/?";


$config['egras_url']="https://uatgras.assam.gov.in/challan/views/frmgrnfordept.php";
$config['egras_grn_cin_url']="https://uatgras.assam.gov.in/challan/models/frmgetgrn.php";

//production
// $config['encryption_key']="1234567890123456";
// $config['vahan_retry_url']="";
// $config['encrypt_url']="http://103.8.249.17:8080/transport-encryption-1.0/application/encrypt/";
// $config['decrypt_url']="http://103.8.249.17:8080/transport-encryption-1.0/application/decrypt/";
$config['encrypt_url']="http://127.0.0.1:8080/transport-encryption-1.0/application/encrypt/";
$config['decrypt_url']="http://127.0.0.1:8080/transport-encryption-1.0/application/decrypt/";
// $config['vahan_status_url']="http://vahan.parivahan.gov.in/knowapplservice/knowappl/fetchyourApplDetails/?";

// $config['egras_url']="";
// $config['egras_grn_cin_url']="";

//fee
$config['service_charge']="30";
$config['printing_charge']="5";
$config['scanning_charge']="10";
