<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// $route['default_controller'] = 'login';
$route['404_override'] = 'errors/notfound404';
// $route['translate_uri_dashes'] = FALSE;
$route['external-portal/login']['GET'] = 'login';
$route['external-portal/send-otp']['POST'] = 'login/send_otp';
$route['external-portal/process-login']['POST'] = 'login/process_login';
$route['external-portal/guidelines']['GET'] = 'intermediator/guidelines';
$route['external-portal/guidelines/(:any)']['GET'] = 'intermediator/guidelines/$1';
$route['external-portal/guidelines/(:any)/(:any)']['GET'] = 'intermediator/guidelines/$1/$2';
$route['external-portal/procced']['POST'] = 'intermediator/procced';
$route['external-portal/retry/(:any)/(:any)/(:any)']['GET'] = 'intermediator/retry/$1/$2/$3';
$route['external-portal/transactions']['GET'] = 'intermediator/transactions';
// $route['external-portal/acknowledgement/(:any)']['GET'] = 'intermediator/acknowledgement/$1';

$route['external-portal/vahan/(:any)']['GET'] = 'vahan/index/$1';
$route['external-portal/vahan/retry/(:any)']['GET'] = 'vahan/retry/$1';
$route['external-portal/v-acknowledgement/(:any)']['GET'] = 'vahan/acknowledgement/$1';
$route['external-portal/o-acknowledgement']['GET'] = 'intermediator/acknowledgement';
// $route['vahan']['GET'] = 'vahan/index/$1';

$route['external-portal/get/response']['GET'] = 'transoprt_response/create_response_new';
$route['external-portal/get/response']['POST'] = 'transoprt_response/create_response_new';
$route['external-portal/get/vahan-response']['GET'] = 'transoprt_response/vahan';
$route['external-portal/get/payment-response'] = 'transoprt_response/payment_response';
$route['external-portal/get/grn-response'] = 'transoprt_response/grn_response';
$route['external-portal/get/cin-response'] = 'transoprt_response/cin_response';
$route['external-portal/check-grn-status/(:any)']['GET'] = 'transoprt_response/checkgrn/$';


$route['external-portal/procced'] = 'intermediator/procced';
$route['external-portal/v-procced'] = 'vahan/procced';

$route['external-portal/status'] = 'status';
$route['external-portal/status/getRequestInfo'] = 'status/getRequestInfo';
$route['external-portal/status/check-status'] = 'status/check_status';
$route['external-portal/status/v-check-status'] = 'status/check_vahan_status';

$route['external-portal/request/create'] = 'request/create';

$route['external-portal/portals'] = 'portals';
$route['external-portal/portals/get_records'] = 'portals/get_records';
$route['external-portal/portals/create'] = 'portals/create';
$route['external-portal/portals/add_action'] = 'portals/add_action';
$route['external-portal/portals/detail/(:any)'] = 'portals/detail/$1';
$route['external-portal/portals/edit/(:any)'] = 'portals/edit/$1';
$route['external-portal/portals/edit_action/(:any)'] = 'portals/edit_action/$1';

$route['example/get-response'] = 'example/get_response';
$route['external-portal/login/logout'] = 'login/logout';
