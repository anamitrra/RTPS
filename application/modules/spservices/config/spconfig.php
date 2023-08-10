<?php
$config['service_charge']="30";
$config['printing_charge']="10";
$config['scanning_charge']="5";
$config['aadhaar_authentication_api']="http://10.194.162.121/aadhaarservice/api/";
$config['edistrict_amtron_charge']="30";
$config['bakijai_certificate_charge']="20";
$config['acmr_noc_application_fee']="5900";
$config['rtps_convenience_fee']="10";

//For testing
$config['url']="http://localhost/cache/webapi_test/"; //Please do not change if you are running at localhost
$config['rtps_url']="https://rtps.assam.gov.in/configure/";
// $config['url']="http://10.177.15.95/webapi_test/";
$config['edistrict_base_url']="http://103.8.249.110:9080/RTPSWebService/";
$config['egras_url']="https://uatgras.assam.gov.in/challan/views/frmgrnfordept.php";
$config['egras_grn_cin_url']="https://uatgras.assam.gov.in/challan/models/frmgetgrn.php";
$config['csc_account']="ARI68751";
$config['egras_fin_year']="2023-2024"; 
$config['rtps_convenience_fee_account']="ARI64576";
$config['dhar_api']="http://localhost/dhar_api/"; //Please do not change if you are running at localhost

//For production
/*
$config['url']="http://10.177.0.33:800/webapi/";
$config['egras_url']="https://assamegras.gov.in/challan/views/frmgrnfordept.php";
$config['egras_grn_cin_url']="https://assamegras.gov.in/challan/models/frmgetgrn.php";
$config['csc_account']="ARI71580";
*/

//eDistrict UAT APIs 
$config['senior_citizen_url']="http://103.8.249.110:9080//RTPSWebService/postApplication?apiKey=un5pdxOw1glKratBgIlL5w&rprm=xyz";
$config['next_of_kin_url']="http://103.8.249.110:9080/RTPSWebService/postApplication?apiKey=un5pdxOw1glKratBgIlL5w&rprm=abc";
$config['delayed_death_url']="http://103.8.249.110:9080/RTPSWebService/postPDDRApplication?apiKey=un5pdxOw1glKratBgIlL5w";
$config['delayed_birth_url']="http://103.8.249.110:9080/RTPSWebService/postPDBRApplication?apiKey=un5pdxOw1glKratBgIlL5w";
$config['bakijai_url']="http://103.8.249.110:9080/RTPSWebService/postBakijaiApplication?apiKey=yrcc3tyb7659l7fnylsy";
$config['income_url']="http://103.8.249.110:9080/RTPSWebService/postApplicationRTPSServices?apiKey=jnp7qwdv105oo66iijxf";
$config['caste_url']="http://103.8.249.110:9080/RTPSWebService/postApplicationRTPSServices?apiKey=hndr5lqiifz0ki3y8iyy";
$config['prc_url']="http://103.8.249.110:9080/RTPSWebService/postApplicationRTPSServices?apiKey=txoc1yv7sqq6cbc2xie0";
$config['ncl_url']="http://103.8.249.110:9080/RTPSWebService/postApplicationRTPSServices?apiKey=gsknka48jy46kdxjrqzd";
$config['building_permission_url']="https://rtps.mmsgna.in/api/";
$config['building_permission_post_url']="https://rtps.mmsgna.in/auth/api/push-app-data";
$config['building_permission_query_submit_url']="https://rtps.mmsgna.in/auth/api/push-app-data";
$config['track_status_url']="http://103.8.249.191:9080/RTPSWebService/track-rtps-application?apiKey=knp7rstv105oo76iijxf";
//NCHAC UAT APIs
$config['nctp_payment_url']="https://nchacartps.in/testsite/update_tp.php";
$config['nctp_post_url']="https://nchacartps.in/testsite/tp_postapplication.php";

//Employment certificate no API
$config['emp_certificate_no_url']="https://rtps.assam.gov.in/apis/empex-api/cert_no.php";
//$config['emp_certificate_no_url']="http://localhost/empex-api/cert_no.php";

//eDistrict PRODUCTION APIs 
/*
$config['senior_citizen_url']="http://103.8.249.191:9080/RTPSWebService/postApplication?apiKey=un5pdxOw1glKratBgIlL5w&rprm=xyz";
$config['next_of_kin_url']="http://103.8.249.191:9080/RTPSWebService/postApplication?apiKey=un5pdxOw1glKratBgIlL5w&rprm=abc";
$config['delayed_death_url']="http://103.8.249.191:9080/RTPSWebService/postPDDRApplication?apiKey=un5pdxOw1glKratBgIlL5w";
$config['delayed_birth_url']="http://103.8.249.191:9080/RTPSWebService/postPDBRApplication?apiKey=un5pdxOw1glKratBgIlL5w";
$config['bakijai_url']="http://103.8.249.191:9080/RTPSWebService/postBakijaiApplication?apiKey=yrcc3tyb7659l7fnylsy&rprm=abc";
$config['income_url']="http://103.8.249.191:9080/RTPSWebService/postApplicationRTPSServices?apiKey=jnp7qwdv105oo66iijxf";
$config['caste_url']="http://103.8.249.191:9080/RTPSWebService/postApplicationRTPSServices?apiKey=hndr5lqiifz0ki3y8iyy";
$config['prc_url']="http://103.8.249.191:9080/RTPSWebService/postApplicationRTPSServices?apiKey=txoc1yv7sqq6cbc2xie0";
$config['ncl_url']="http://103.8.249.191:9080/RTPSWebService/postApplicationRTPSServices?apiKey=gsknka48jy46kdxjrqzd";
$config['track_status_url']="http://103.8.249.191:9080/RTPSWebService/
track-rtps-application?apiKey=knp7rstv105oo76iijxf";
*/
$config['ncl_edistrict_fetch_url']="http://103.8.249.191:9080/RTPSWebService/getApplicationCertificateData";

//For eGRAS payment
$config['dept_code']="ARI";
$config['office_code']="ARI000";
$config['to_date']="31/03/2099";
$config['non_trea_pmt_type']="02";
$config['sub_system']="ARTPS-SP|" . base_url('spservices/employment_aadhaar_based/PaymentResponse/response');

//ACMR services
//$config['sub_system']="ARTPS-SP|" . base_url('spservices/acmr_cp_cme/payment_response/response');                                   
// $config['sub_system']="ARTPS-SP|" . base_url('spservices/employment_aadhaar_base/PaymentResponse/response');
//ACMR PROVISIONAL CERTIFICATE
// $config['sub_system']="ARTPS-SP|" . base_url('spservices/acmr_provisional_certificate/payment_response/response');
//kaac
$config['kaac_post_url']="http://artpskaac.in/testsite/";
$config['kaac_query_account']="ITD75175";
$config['kaac_dept_code']="ITD";
$config['kaac_office_code']="ITD009";
$config['kaac_department_name']="Karbi Anglong (AC)";
$config['kaac_department_id']="2100";
// Live Url
// $config['kaac_post_url']="http://artpskaac.in/"; 
 
