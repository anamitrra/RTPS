<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['profile'] = 'profile';
//Admin Profile
$route['admin/profile'] = 'profile/profiles/index';
$route['admin/profile/update'] = 'profile/profiles/update';
$route['admin/password'] = 'profile/profiles/password';
$route['admin/password/update'] = 'profile/profiles/password_update';
$route['admin/profile/photo'] = 'profile/profiles/upload_photo';
$route['admin/profile/photo/remove'] = 'profile/profiles/remove_photo';
