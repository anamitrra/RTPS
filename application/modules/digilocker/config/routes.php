<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// $route['default_controller'] = 'login';
$route['404_override'] = 'errors/notfound404';
$route['digilocker']['GET'] = 'digilocker';
$route['digilocker/push-uri'] = 'Digilocker_pushcontroller/saveDigilockerId';


