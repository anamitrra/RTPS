<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['404_override'] = 'errors/notfound404';

$route['site'] = 'site/index';
$route['site/service-apply/(:any)'] = 'site/kys/$1';
$route['site/lang/(:any)'] = 'language/index/$1';
$route['site/theme/(:any)'] = 'language/mode/$1';
$route['site/service-wise-data'] = 'online/service_wise_data';
$route['site/online/basundhara'] = 'online/basundhara';
$route['site/online/utility'] = 'online/utility';
$route['site/online/eodb'] = 'online/eodb';
$route['site/online/citizen'] = 'online/citizenservice';
$route['site/online/services-by-category/(:num)/(:any)']['GET'] = 'online/get_services_by_subcateg/$1/$2';
$route['site/online/(:any)'] = 'online/index/$1';
$route['site/third-party-verification'] = 'Thirdpartyverification/index';
$route['site/third-party-verification/get_details']['POST'] = 'Thirdpartyverification/get_details';

/* Portal Admin Routes */
 