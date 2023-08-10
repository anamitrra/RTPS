<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller'] = 'site';
$route['404_override'] = 'errors/notfound404';
$route['translate_uri_dashes'] = FALSE;

// uploads
$route['upload']['POST'] = 'upload/index';
$route['shedule/sms'] = 'sms_scheduler/send_queue';

// demo routes
require_once APPPATH.'/routes/demo.php';

//API Routes
$route['api/(:any)'] = 'frontend/api/api/index/$1';
$route['api/v2/(:any)'] = 'frontend/api/api/get_data/$1';
