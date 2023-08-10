<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'login';
$route['404_override'] = 'errors/notfound404';
$route['translate_uri_dashes'] = FALSE;
$route['expr']['GET'] = 'login';
$route['expr/login']['GET'] = 'login';
$route['expr/services']['GET'] = 'login/services';
$route['expr/send-otp']['POST'] = 'login/send_otp';
$route['expr/process-login']['POST'] = 'login/process_login';
$route['expr/guidelines']['GET'] = 'intermediator/guidelines';
$route['expr/guidelines/(:any)']['GET'] = 'intermediator/guidelines/$1';
$route['expr/guidelines/(:any)/(:any)']['GET'] = 'intermediator/guidelines/$1/$2';
$route['expr/procced']['POST'] = 'intermediator/procced';
$route['expr/retry/(:any)/(:any)/(:any)']['GET'] = 'intermediator/retry/$1/$2/$3';
$route['expr/transactions']['GET'] = 'intermediator/transactions';
// $route['expr/acknowledgement/(:any)']['GET'] = 'intermediator/acknowledgement/$1';

$route['expr/vahan/(:any)']['GET'] = 'vahan/index/$1';
$route['expr/vahan-service/retry/(:any)']['GET'] = 'vahan/retry/$1';
$route['expr/v-acknowledgement/(:any)']['GET'] = 'vahan/acknowledgement/$1';
$route['expr/o-acknowledgement']['GET'] = 'intermediator/acknowledgement';
// $route['vahan']['GET'] = 'vahan/index/$1';

$route['expr/get/response']['GET'] = 'transoprt_response/create_response_new';
$route['expr/get/response']['POST'] = 'transoprt_response/create_response_new';
$route['expr/get/vahan-response']['GET'] = 'transoprt_response/vahan';
$route['expr/get/payment-response'] = 'transoprt_response/payment_response';
$route['expr/get/grn-response'] = 'transoprt_response/grn_response';
$route['expr/get/cin-response'] = 'transoprt_response/cin_response';
$route['expr/check-grn-status/(:any)']['GET'] = 'transoprt_response/checkgrn/$';


$route['expr/procced'] = 'intermediator/procced';
$route['expr/v-procced'] = 'vahan/procced';

$route['expr/status'] = 'status';
$route['expr/status/getRequestInfo'] = 'status/getRequestInfo';
$route['expr/status/check-status'] = 'status/check_status';
$route['expr/status/v-check-status'] = 'status/check_vahan_status';

$route['expr/request/create'] = 'request/create';

$route['expr/portals'] = 'portals';
$route['expr/portals/get_records'] = 'portals/get_records';
$route['expr/portals/create'] = 'portals/create';
$route['expr/portals/add_action'] = 'portals/add_action';
$route['expr/portals/detail/(:any)'] = 'portals/detail/$1';
$route['expr/portals/edit/(:any)'] = 'portals/edit/$1';
$route['expr/portals/edit_action/(:any)'] = 'portals/edit_action/$1';

$route['example/get-response'] = 'example/get_response';

$route['expr/track-vahan'] = 'Status_tracking/vahan';


// admin url
$route['expr/admin']['GET'] = 'admin/login';
$route['expr/admin/send-otp']['POST'] = 'admin/login/send_otp';
$route['expr/admin/process-login']['POST'] = 'admin/login/process_login';
$route['expr/admin/login']['GET'] = 'admin/login/index';
$route['expr/login/loginMe']['POST'] = 'admin/login/loginMe';
$route['expr/admin/roles']['GET'] = 'admin/roles';
$route['expr/admin/dashboard']['GET'] = 'admin/dashboard/index';
$route['expr/admin/roles/get_records']['POST'] = 'admin/roles/get_records';

$route['expr/admin/users']['GET'] = 'admin/users';
$route['expr/admin/users/get_records']['POST'] = 'admin/users/get_records';
$route['expr/admin/users/read/(:any)']['GET'] = 'admin/users/read/$1';
$route['expr/admin/users/update/(:any)']['GET'] = 'admin/users/update/$1';
$route['expr/admin/users/delete/(:any)']['GET'] = 'admin/users/delete/$1';

$route['expr/admin/departments']['GET'] = 'admin/departments';
$route['expr/admin/Portals']['GET'] = 'portals';

$route['expr/admin/services']['GET'] = 'admin/services';
$route['expr/admin/vahan/(:any)']['GET'] = 'admin/vahan/index/$1';
$route['expr/admin/vahan-service/retry/(:any)']['GET'] = 'admin/vahan/retry/$1';
$route['expr/admin/v-acknowledgement/(:any)']['GET'] = 'admin/vahan/acknowledgement/$1';
$route['expr/admin/o-acknowledgement']['GET'] = 'admin/intermediator/acknowledgement';
$route['expr/admin/v-procced']['POST'] = 'admin/vahan/procced';
$route['expr/admin/verify-user/send-otp'] = 'admin/vahan/send_sms_otp_to_verify_user';

$route['expr/admin/my-transactions']['GET'] = 'admin/transactions';
$route['expr/admin/get/vahan-response']['GET'] = 'admin/transoprt_response/vahan';
$route['expr/admin/get/payment-response']['POST'] = 'admin/transoprt_response/payment_response';
$route['expr/admin/get-acknowledgement']['GET'] = 'admin/vahan/acknowledgement';
$route['expr/admin/update-pfc-payment-amount']['POST'] = 'admin/transoprt_response/update_pfc_payment_amount';
$route['expr/admin/update-retry-pfc-payment-amount']['POST'] = 'admin/transoprt_response/update_retry_pfc_payment_amount';

$route['expr/admin/get/grn-response'] = 'admin/transoprt_response/grn_response';
$route['expr/admin/get/cin-response'] = 'admin/transoprt_response/cin_response';
$route['expr/admin/check-grn-status/(:any)']['GET'] = 'admin/transoprt_response/checkgrn/$1';
$route['expr/admin/pfc-payment/(:any)']['GET'] = 'admin/PFC_Payment/payment/$1';
$route['expr/admin/retry-pfc-payment/(:any)']['GET'] = 'admin/PFC_Payment/retry_payment/$1';

$route['expr/admin/guidelines/(:any)/(:any)']['GET'] = 'admin/intermediator/guidelines/$1/$2';
$route['expr/admin/procced']['POST'] = 'admin/intermediator/procced';
$route['expr/admin/get/response']['POST'] = 'admin/transoprt_response/create_response_new';

$route['expr/admin/retry/(:any)/(:any)/(:any)']['GET'] = 'admin/intermediator/retry/$1/$2/$3';
$route['expr/admin/profile']['GET'] = 'admin/profiles/index';
$route['expr/admin/profile/update']['POST'] = 'admin/profiles/update';
