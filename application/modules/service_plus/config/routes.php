<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['service_plus/test']['GET'] = 'test/index';

// Service Plus
$route['service_plus/view']['GET'] = 'service_plus/index';
$route['service_plus/get-records']['POST'] = 'service_plus/get_records';

$route['service_plus/load_dashboard'] = 'service_plus/load_dashboard';
$route['service_plus/access_denied'] = 'service_plus/access_denied';
$route['service_plus/login/forgot-password'] = 'login/forgotPassword';
$route['service_plus/reset-password/(:any)/(:any)'] = 'login/resetPasswordConfirmUser/$1/$2';
$route['service_plus/reset-password/process'] = 'login/createPasswordUser';
$route['service_plus/logout'] = 'service_plus/logout';

// users
$route['service_plus/users'] = 'users';
$route['service_plus/users/get_records'] = 'users/get_records';
$route['service_plus/users/create'] = 'users/create';
$route['service_plus/users/create_action'] = 'users/create_action';
$route['service_plus/users/read/(:any)'] = 'users/read/$1';
$route['service_plus/users/update/(:any)'] = 'users/update/$1';
$route['service_plus/users/update_action'] = 'users/update_action';
$route['service_plus/users/delete/(:any)'] = 'users/delete/$1';

// Profile
$route['service_plus/profile']['GET']  = 'profiles/index';
$route['service_plus/profile/update']['POST']  = 'profiles/update';
$route['service_plus/profile/password']['GET'] = 'profiles/password';
$route['service_plus/profile/password/update']['POST'] = 'profiles/password_update';
$route['service_plus/profile/photo'] = 'profiles/upload_photo';
$route['service_plus/profile/photo/remove'] = 'profiles/remove_photo';

// roles
$route['mis/roles'] = 'roles';
$route['mis/roles/get_records'] = 'roles/get_records';
$route['mis/roles/add'] = 'roles/add';
$route['mis/roles/get_role_info'] = 'roles/get_role_info';
$route['mis/roles/update'] = 'roles/update';
