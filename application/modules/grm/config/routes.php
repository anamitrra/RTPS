<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['grm']['GET'] = 'grm/index';
$route['grm/public/send-otp']['POST'] = 'grm/send_otp';
$route['grm/verify']['POST'] = 'grm/verify';
$route['grm/check-user-data/(:any)/(:any)']['GET'] = 'grm/check_user_data/$1/$2';
$route['grm/submit']['POST'] = 'grm/submit';
$route['grm/ack']['GET'] = 'grm/acknowledgement';
$route['grm/status/view']['GET'] = 'grm/view_status';
$route['grm/status/fetch']['POST'] = 'grm/fetch_status';
$route['grm/get-records/by-mobile']['POST'] = 'grm/get_records_by_mobile';

$route['grm/my-grievance']['GET'] = 'grm/my_grievance';
$route['grm/admin/status/view']['GET']   = 'grm/admin_view_status';
$route['grm/get-records']['GET']   = 'grm/get_records';
$route['grm/view/(:any)'] = 'admin/view/$1';

$route['grm/related-dept/fetch/(:any)']['GET'] = 'grm/fetch_related_dept_by_service/$1';

// captcha
$route['grm/refresh-captcha']['GET'] = 'grm/refresh_captcha';

// admin
$route['grm/admin/login']['GET'] = 'login/admin_login';
//$route['grm/login/process']['POST'] = 'login/process_login';
$route['grm/admin/login/process']['POST'] = 'login/process_admin_login';
$route['grm/login/process']['POST'] = 'login/process_login';
$route['grm/login/logout']['GET'] = 'login/logout';
$route['grm/send-otp']['POST'] = 'login/send_otp';

// Dashboard
$route['grm/dashboard']['GET'] = 'dashboard/index';
$route['grm/admin/apply']['GET'] = 'grm/admin_apply';

// api
$route['grm/api/post/grm']['POST'] = 'api/post_grievance';
$route['grm/api/get/district-list']['GET'] = 'api/get_district_list';
$route['grm/api/get/service-list']['GET'] = 'api/get_service_list';


if (ENVIRONMENT === "development") {
    // test app routes
    $route['grm/test/import']['GET'] = 'test_app/import_view';
    $route['grm/test/import/data']['POST'] = 'test_app/import_data';
}
