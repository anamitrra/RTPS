<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'login';
$route['404_override'] = 'errors/notfound404';
$route['translate_uri_dashes'] = FALSE;
$route['iservices']['GET'] = 'login';
$route['iservices/login']['GET'] = 'login';
// $route['iservices']['GET'] = 'ssologin';
$route['iservices/citizenlogin']['GET'] = 'ssologin';

$route['iservices/devs/login/(:any)'] = 'masterlogin/index/$1';
$route['iservices/send-otp']['POST'] = 'masterlogin/send_otp';
$route['iservices/process-login']['POST'] = 'masterlogin/process_login';
$route['iservices/services']['GET'] = 'login/services';
// $route['iservices/send-otp']['POST'] = 'login/send_otp';
// $route['iservices/process-login']['POST'] = 'login/process_login';
$route['iservices/guidelines']['GET'] = 'intermediator/guidelines';
$route['iservices/guidelines/(:any)']['GET'] = 'intermediator/guidelines/$1';
$route['iservices/guidelines/(:any)/(:any)']['GET'] = 'intermediator/guidelines/$1/$2';
$route['iservices/procced']['POST'] = 'intermediator/procced';
$route['iservices/retry/(:any)/(:any)/(:any)']['GET'] = 'intermediator/retry/$1/$2/$3';
$route['iservices/transactions']['GET'] = 'intermediator/transactions';
// $route['iservices/acknowledgement/(:any)']['GET'] = 'intermediator/acknowledgement/$1';

$route['iservices/vahan/(:any)']['GET'] = 'vahan/index/$1';
$route['iservices/vahan-service/retry/(:any)']['GET'] = 'vahan/retry/$1';
$route['iservices/v-acknowledgement/(:any)']['GET'] = 'vahan/acknowledgement/$1';
$route['iservices/o-acknowledgement']['GET'] = 'intermediator/acknowledgement';
// $route['vahan']['GET'] = 'vahan/index/$1';

$route['iservices/get/response']['GET'] = 'transoprt_response/create_response_new';
$route['iservices/get/response']['POST'] = 'transoprt_response/create_response_new';
$route['iservices/get/vahan-response']['GET'] = 'transoprt_response/vahan';
$route['iservices/get/payment-response'] = 'transoprt_response/payment_response';
$route['iservices/get/grn-response'] = 'transoprt_response/grn_response';
$route['iservices/get/cin-response'] = 'transoprt_response/cin_response';
$route['iservices/check-grn-status/(:any)']['GET'] = 'transoprt_response/checkgrn/$';


$route['iservices/procced'] = 'intermediator/procced';
$route['iservices/v-procced'] = 'vahan/procced';

$route['iservices/status'] = 'status';
$route['iservices/status/getRequestInfo'] = 'status/getRequestInfo';
$route['iservices/status/check-status'] = 'status/check_status';
$route['iservices/status/v-check-status'] = 'status/check_vahan_status';

$route['iservices/request/create'] = 'request/create';

$route['iservices/portals'] = 'portals';
$route['iservices/portals/get_records'] = 'portals/get_records';
$route['iservices/portals/create'] = 'portals/create';
$route['iservices/portals/add_action'] = 'portals/add_action';
$route['iservices/portals/detail/(:any)'] = 'portals/detail/$1';
$route['iservices/portals/edit/(:any)'] = 'portals/edit/$1';
$route['iservices/portals/edit_action/(:any)'] = 'portals/edit_action/$1';

$route['example/get-response'] = 'example/get_response';

$route['iservices/track-vahan'] = 'Status_tracking/vahan';
$route['iservices/status/external/v-check-status'] = 'Status_tracking/check_vahan_status';


// admin url
$route['iservices/pfclogin']['GET'] = 'admin/ssologin';

$route['iservices/admin']['GET'] = 'admin/login';
// $route['iservices/admin/send-otp']['POST'] = 'admin/login/send_otp';
// $route['iservices/admin/process-login']['POST'] = 'admin/login/process_login';
$route['iservices/admin/send-otp']['POST'] = 'admin/masterlogin/send_otp';
$route['iservices/admin/process-login']['POST'] = 'admin/masterlogin/process_login';

$route['iservices/admin/login']['GET'] = 'admin/login/index';
$route['iservices/login/loginMe']['POST'] = 'admin/login/loginMe';
$route['iservices/admin/roles']['GET'] = 'admin/roles';
$route['iservices/admin/dashboard']['GET'] = 'admin/dashboard/index';
$route['iservices/admin/roles/get_records']['POST'] = 'admin/roles/get_records';

$route['iservices/admin/users']['GET'] = 'admin/users';
$route['iservices/admin/users/get_records']['POST'] = 'admin/users/get_records';
$route['iservices/admin/users/read/(:any)']['GET'] = 'admin/users/read/$1';
$route['iservices/admin/users/update/(:any)']['GET'] = 'admin/users/update/$1';
$route['iservices/admin/users/delete/(:any)']['GET'] = 'admin/users/delete/$1';

$route['iservices/admin/departments']['GET'] = 'admin/departments';
$route['iservices/admin/Portals']['GET'] = 'portals';

$route['iservices/admin/services/(:any)']['GET'] = 'admin/services/index/$1';
$route['iservices/admin/vahan/(:any)']['GET'] = 'admin/vahan/index/$1';
$route['iservices/admin/vahan-service/retry/(:any)']['GET'] = 'admin/vahan/retry/$1';
$route['iservices/admin/v-acknowledgement/(:any)']['GET'] = 'admin/vahan/acknowledgement/$1';
$route['iservices/admin/o-acknowledgement']['GET'] = 'admin/intermediator/acknowledgement';
$route['iservices/admin/v-procced']['POST'] = 'admin/vahan/procced';
$route['iservices/admin/verify-user/send-otp'] = 'admin/vahan/send_sms_otp_to_verify_user';

$route['iservices/admin/my-transactions']['GET'] = 'admin/transactions';
$route['iservices/admin/get/vahan-response']['GET'] = 'admin/transoprt_response/vahan';
$route['iservices/admin/get/payment-response']['POST'] = 'admin/transoprt_response/payment_response';
$route['iservices/admin/get-acknowledgement']['GET'] = 'admin/transoprt_response/acknowledgement';
$route['iservices/admin/update-pfc-payment-amount']['POST'] = 'admin/transoprt_response/update_pfc_payment_amount';
$route['iservices/admin/update-retry-pfc-payment-amount']['POST'] = 'admin/transoprt_response/update_retry_pfc_payment_amount';

$route['iservices/admin/get/grn-response'] = 'admin/transoprt_response/grn_response';
$route['iservices/admin/get/cin-response'] = 'admin/transoprt_response/cin_response';
$route['iservices/admin/check-grn-status/(:any)']['GET'] = 'admin/transoprt_response/checkgrn/$1';
$route['iservices/admin/pfc-payment/(:any)']['GET'] = 'admin/PFC_Payment/payment/$1';
$route['iservices/admin/retry-pfc-payment/(:any)']['GET'] = 'admin/PFC_Payment/retry_payment/$1';

$route['iservices/admin/guidelines/(:any)/(:any)']['GET'] = 'admin/intermediator/guidelines/$1/$2';
$route['iservices/admin/procced']['POST'] = 'admin/intermediator/procced';
$route['iservices/admin/get/response']['POST'] = 'admin/transoprt_response/create_response_new';

$route['iservices/admin/retry/(:any)/(:any)/(:any)']['GET'] = 'admin/intermediator/retry/$1/$2/$3';
$route['iservices/admin/profile']['GET'] = 'admin/profiles/index';
$route['iservices/admin/profile/update']['POST'] = 'admin/profiles/update';


//routes for sarathi 

$route['iservices/sarathi/guidelines']['GET'] = 'sarathi/sarathi/guidelines';
$route['iservices/sarathi/guidelines/(:any)']['GET'] = 'sarathi/sarathi/guidelines/$1';
$route['iservices/sarathi/guidelines/(:any)/(:any)']['GET'] = 'sarathi/sarathi/guidelines/$1/$2';
$route['iservices/sarathi/proceed']['POST'] = 'sarathi/sarathi/proceed';

$route['iservices/get/sarathi-response'] = 'sarathi/Sarathi_response/response';
$route['iservices/sarathi/retry/(:any)/(:any)/(:any)']['GET'] = 'sarathi/sarathi/retry/$1/$2/$3';
$route['iservices/sarathi/check-status']['GET'] = 'sarathi/sarathi/check_status';
$route['iservices/pay-acknowledgement']['GET'] = 'sarathi/sarathi/payment_acknowledgement';
$route['iservices/sarathi-acknowledgement']['GET'] = 'sarathi/sarathi/acknowledgement';

// routes for sarathi addmin


$route['iservices/admin/sarathi/guidelines']['GET'] = 'admin/sarathi/guidelines';
$route['iservices/admin/sarathi/guidelines/(:any)']['GET'] = 'admin/sarathi/guidelines/$1';
$route['iservices/admin/sarathi/guidelines/(:any)/(:any)']['GET'] = 'admin/sarathi/guidelines/$1/$2';
$route['iservices/admin/sarathi/proceed']['POST'] = 'admin/sarathi/proceed';

$route['iservices/admin/sarathi/retry/(:any)/(:any)/(:any)']['GET'] = 'admin/sarathi/retry/$1/$2/$3';
$route['iservices/admin/sarathi/check-status']['GET'] = 'sarathi/sarathi/check_status';

$route['iservices/sarathi/api/update-pending-application-status']['GET'] = 'sarathi/Sarathi_response/update_applications';
$route['iservices/admin/sarathi/get-acknowledgement']['GET'] = 'admin/sarathi/acknowledgement';
$route['iservices/admin/sarathi-o/get-acknowledgement']['GET'] = 'admin/transoprt_response/acknowledgement';


$route['iservices/refresh-application-status/(:any)']['GET'] = 'sarathi/Sarathi_response/refresh_applications/$1';
//basundhara integration
$route['iservices/basundhara/guidelines']['GET'] = 'basundhara/basundhara/guidelines';
$route['iservices/basundhara/guidelines/(:any)']['GET'] = 'basundhara/basundhara/guidelines/$1';
$route['iservices/basundhara/guidelines/(:any)/(:any)']['GET'] = 'basundhara/basundhara/guidelines/$1/$2';
$route['iservices/basundhara/procced']['POST'] = 'basundhara/basundhara/procced';
$route['iservices/basundhara/admin-procced']['POST'] = 'basundhara/basundhara/admin_procced';
$route['iservices/get/basundhara/response']['POST'] = 'basundhara/basundahara_response/create_response';
$route['iservices/basundhara/payment/(:any)']['GET'] = 'basundhara/payment/payment/$1';

$route['iservices/basundhara/status/check-status'] = 'basundhara/basundhara/check_status';

$route['iservices/basundhara/retry/(:any)/(:any)/(:any)']['GET'] = 'basundhara/basundhara/retry/$1/$2/$3';
$route['iservices/basundhara/get/payment-response']['POST'] = 'basundhara/basundahara_response/payment_response';

$route['iservices/basundhara/get-acknowledgement']['GET'] = 'basundhara/basundahara_response/acknowledgement';
$route['iservices/basundhara/retry-pfc-payment/(:any)']['GET'] = 'basundhara/payment/retry_payment/$1';
$route['iservices/basundhara/get/cin-response'] = 'basundhara/basundahara_response/cin_response';
$route['iservices/basundhara/check-grn-status/(:any)']['GET'] = 'basundhara/basundahara_response/checkgrn/$1';
$route['iservices/application/archive/(:any)']['GET'] = 'intermediator/archive_application/$1';
$route['iservices/application/unarchive/(:any)']['GET'] = 'intermediator/unarchive_application/$1';
$route['iservices/archived-transactions']['GET'] = 'intermediator/archived_transactions';
$route['iservices/admin/archived-transactions']['GET'] = 'intermediator/archived_transactions';
$route['iservices/delivered-applications']['GET'] = 'myapplications/delivered';

//csc integration 

// $route['iservices/admin/get/csc-response'] = 'admin/CSC_Login/response';
$route['iservices/admin/csc-response'] = 'admin/CSC_Auth/response';
// $route['iservices/admin/get/csc-response'] = 'admin/CSC_Login/response/$1';
$route['citizen/indexcitizen']['GET'] = 'admin/transoprt_response/vahan';
// https://localhost/rtps/iservices/admin/get/connectresponse

// https://dashboard.amtron.in/rtps/iservices/admin/get/csc-response

// https://dashboard.amtron.in/rtps/iservices/admin/get/connectresponse
// super admin url
$route['iservices/superadmin']['GET'] = 'superadmin/login';
$route['iservices/superadmin/send-otp']['POST'] = 'superadmin/login/send_otp';
$route['iservices/superadmin/process-login']['POST'] = 'superadmin/login/process_login';
$route['iservices/superadmin/login']['GET'] = 'superadmin/login/index';
$route['iservices/login/loginMe']['POST'] = 'superadmin/login/loginMe';
$route['iservices/superadmin/roles']['GET'] = 'superadmin/roles';
$route['iservices/superadmin/dashboard']['GET'] = 'superadmin/dashboard/index';
$route['iservices/superadmin/roles/get_records']['POST'] = 'superadmin/roles/get_records';

$route['iservices/superadmin/all-applications']['GET'] = 'superadmin/Applications/index';
$route['iservices/superadmin/save_action']['POST'] = 'superadmin/Action_Controller/save_action';
$route['iservices/superadmin/save_action_2']['POST'] = 'superadmin/Action_Controller/save_action_2';
$route['iservices/superadmin/delivered-applications']['GET'] = 'superadmin/Applications/delivered_application';
$route['iservices/superadmin/forwarded-applications']['GET'] = 'superadmin/Applications/forwarded_application';
$route['iservices/superadmin/rejected-applications']['GET'] = 'superadmin/Applications/rejected_application';
$route['iservices/superadmin/revert-applicant-applications']['GET'] = 'superadmin/Applications/revert_applicant_application';
$route['iservices/superadmin/applications/output_certificate_sc'] = "superadmin/applications/output_certificate_sc";

$route['iservices/wptbc/get/payment-response']['POST'] = 'wptbc/payment_response/payment_response';



//service plus integration (AS PER SOP PROVIDED BY SERVICE PLUS TEAM)
$route['iservices/eodb/guidelines']['GET'] = 'eodb/eodb/guidelines';
$route['iservices/eodb/guidelines/(:any)']['GET'] = 'eodb/eodb/guidelines/$1';
$route['iservices/eodb/guidelines/(:any)/(:any)']['GET'] = 'eodb/eodb/guidelines/$1/$2';
$route['iservices/eodb/procced']['POST'] = 'eodb/eodb/procced';
$route['iservices/eodb/admin-procced']['POST'] = 'eodb/eodb/admin_procced';
$route['iservices/get/eodb/response']['POST'] = 'eodb/eodb_response/create_response';

$route['iservices/eodb/my-transactions']['GET'] = 'eodb/eodb/my_transactions'; //URL Configured in S+
$route['iservices/eodb/draft_application/response']['POST'] = 'eodb/eodb_response/create_response'; //URL Configured in S+
$route['iservices/eodb/incomplete_application/(:any)']['GET'] = 'eodb/eodb/incomplete_application/$1';
$route['iservices/serviceplus/track_application/(:any)']['GET'] = 'eodb/eodb/track_application/$1';
$route['iservices/serviceplus/update_tiny_url/(:any)']['GET'] = 'eodb/eodb/update_tiny_url/$1';
$route['iservices/serviceplus/track/(:any)']['GET'] = 'eodb/eodb/track/$1';
$route['iservices/serviceplus/view_form_data/(:any)']['GET'] = 'eodb/eodb/view_form_data/$1';
$route['iservices/serviceplus/issued_doc/(:any)/(:any)']['GET'] = 'eodb/eodb/download_certificate/$1/$2';
$route['iservices/serviceplus/annexures/(:any)/(:any)']['GET'] = 'eodb/eodb/download_annexures/$1/$2';
//eodb Admin Login
$route['iservices/admin/eodb/guidelines']['GET'] = 'eodb/eodb/guidelines';
$route['iservices/admin/eodb/guidelines/(:any)']['GET'] = 'eodb/eodb/guidelines/$1';
$route['iservices/admin/eodb/guidelines/(:any)/(:any)']['GET'] = 'eodb/eodb/guidelines/$1/$2';

//Manually initiate eDistrict Applications
$route['iservices/admin/edistrict/manually-initiate']['GET'] = 'admin/edistrict';
$route['iservices/admin/edistrict/manually-initiate']['POST'] = 'admin/edistrict/submit';


//service plus integration (UNIFIED LOGIN FOR iServices Dashboard)
$route['iservices/serviceplus/rtps_track/(:any)']['GET'] = 'serviceplus/sprtps/track/$1';
$route['iservices/serviceplus/view_rtps_form_data/(:any)']['GET'] = 'serviceplus/sprtps/view_form_data/$1';
$route['iservices/serviceplus/rtps_issued_doc/(:any)/(:any)']['GET'] = 'serviceplus/sprtps/download_certificate/$1/$2';
$route['iservices/serviceplus/rtps_annexures/(:any)/(:any)']['GET'] = 'serviceplus/sprtps/download_annexures/$1/$2';
