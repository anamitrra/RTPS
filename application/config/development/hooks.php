<?php
defined('BASEPATH') or exit('No direct script access allowed');

// $hook['pre_controller'] = array(
//     'class' => 'Language1',
//     'function' => 'index',
//     'filename' => 'Language1.php',
//     'filepath' => 'modules/portal/controllers/',
// );
//$hook['pre_controller'] = array(  
//   'class' => 'Authorise',  
//   'function' => 'restriction',  
//    'filename' => 'Authorise.php',  
//    'filepath' => 'modules/appeal/controllers' 
//    );  

$hook['post_controller_constructor'] = array(
   'class'    => 'LanguageLoader',
   'function' => 'initialize',
   'filename' => 'LanguageLoader.php',
   'filepath' => 'hooks'
);

// $hook['pre_controller'] = array(
//    'class'    => 'Redirection',
//    'function' => 'checkUri',
//    'filename' => 'Redirection.php',
//    'filepath' => 'hooks'
// );
