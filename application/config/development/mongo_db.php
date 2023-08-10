<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------------
| This file will contain the settings needed to access your Mongo database.
|
|
| ------------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| ------------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['write_concerns'] Default is 1: acknowledge write operations.  ref(http://php.net/manual/en/mongo.writeconcerns.php)
|	['journal'] Default is TRUE : journal flushed to disk. ref(http://php.net/manual/en/mongo.writeconcerns.php)
|	['read_preference'] Set the read preference for this connection. ref (http://php.net/manual/en/mongoclient.setreadpreference.php)
|	['read_preference_tags'] Set the read preference for this connection.  ref (http://php.net/manual/en/mongoclient.setreadpreference.php)
|
| The $config['mongo_db']['active'] variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
*/
$CI = get_instance();
$module = strval( $CI->uri->segment(1, 0));
//pre($module);

switch ($module){
    case 'site':
        $config['mongo_db']['active'] = 'default';
        break;

    case 'mis':
        $config['mongo_db']['active'] = 'mis';
        break;
    case 'grm':
        $config['mongo_db']['active'] = 'grievances';
        break;
    case 'sadbhawana':
        $config['mongo_db']['active'] = 'sadbhawana';
        break;
    case 'iservices':
        $config['mongo_db']['active'] = 'expr';
        break;
     case 'spservices':
        $config['mongo_db']['active'] = 'expr';
        break;
    case 'appeal':
        $config['mongo_db']['active'] = 'appeal';
        break;
    case 'service_plus':
        $config['mongo_db']['active'] = 'service_plus';
        break;
    case 'digilocker':
            $config['mongo_db']['active'] = 'expr';
        break;
    default:
        $config['mongo_db']['active'] = 'default';
    break;
}
//pre($config);
//if($module=='appeal'){
//    $config['mongo_db']['active'] = 'default';
//}else if($module=='dashboard'){
//    $config['mongo_db']['active'] = 'applications';
//}else{
//    $config['mongo_db']['active'] = 'default';
//}


//DB configuration for appeal
$config['mongo_db']['default']['no_auth'] = true;
$config['mongo_db']['default']['hostname'] = 'localhost';
$config['mongo_db']['default']['port'] = '27017';
$config['mongo_db']['default']['username'] = '';
$config['mongo_db']['default']['password'] = '';
$config['mongo_db']['default']['database'] = 'portal';
$config['mongo_db']['default']['db_debug'] = TRUE;
$config['mongo_db']['default']['return_as'] = 'object';
$config['mongo_db']['default']['write_concerns'] = (int)1;
$config['mongo_db']['default']['journal'] = TRUE;
$config['mongo_db']['default']['read_preference'] = 'primary';
$config['mongo_db']['default']['read_concern'] = 'local'; //'local', 'majority' or 'linearizable'
$config['mongo_db']['default']['legacy_support'] = TRUE;

//DB configuration for dashboard
$config['mongo_db']['mis']['no_auth'] = true;
$config['mongo_db']['mis']['hostname'] = 'localhost';
$config['mongo_db']['mis']['port'] = '27017';
$config['mongo_db']['mis']['username'] = '';
$config['mongo_db']['mis']['password'] = '';
$config['mongo_db']['mis']['database'] = 'mis';
$config['mongo_db']['mis']['db_debug'] = TRUE;
$config['mongo_db']['mis']['return_as'] = 'object';
$config['mongo_db']['mis']['write_concerns'] = (int)1;
$config['mongo_db']['mis']['journal'] = TRUE;
$config['mongo_db']['mis']['read_preference'] = 'primary';
$config['mongo_db']['mis']['read_concern'] = 'local'; //'local', 'majority' or 'linearizable'
$config['mongo_db']['mis']['legacy_support'] = TRUE;

////DB configuration for public grm
$config['mongo_db']['grievances']['no_auth']         = true;
$config['mongo_db']['grievances']['hostname']        = 'localhost';
$config['mongo_db']['grievances']['port']            = '27017';
$config['mongo_db']['grievances']['username']        = '';
$config['mongo_db']['grievances']['password']        = '';
$config['mongo_db']['grievances']['database']        = 'grievances';
$config['mongo_db']['grievances']['db_debug']        = TRUE;
$config['mongo_db']['grievances']['return_as']       = 'object';
$config['mongo_db']['grievances']['write_concerns']  = (int)1;
$config['mongo_db']['grievances']['journal']         = TRUE;
$config['mongo_db']['grievances']['read_preference'] = 'primary';
$config['mongo_db']['grievances']['read_concern']    = 'local'; //'local', 'majority' or 'linearizable'
$config['mongo_db']['grievances']['legacy_support']  = TRUE;

////DB configuration for public sadbhawana
$config['mongo_db']['sadbhawana']['no_auth']         = true;
$config['mongo_db']['sadbhawana']['hostname']        = 'localhost';
$config['mongo_db']['sadbhawana']['port']            = '27017';
$config['mongo_db']['sadbhawana']['username']        = '';
$config['mongo_db']['sadbhawana']['password']        = '';
$config['mongo_db']['sadbhawana']['database']        = 'sadbhawana';
$config['mongo_db']['sadbhawana']['db_debug']        = TRUE;
$config['mongo_db']['sadbhawana']['return_as']       = 'object';
$config['mongo_db']['sadbhawana']['write_concerns']  = (int)1;
$config['mongo_db']['sadbhawana']['journal']         = TRUE;
$config['mongo_db']['sadbhawana']['read_preference'] = 'primary';
$config['mongo_db']['sadbhawana']['read_concern']    = 'local'; //'local', 'majority' or 'linearizable'
$config['mongo_db']['sadbhawana']['legacy_support']  = TRUE;

////DB configuration for external portal
$config['mongo_db']['external-portal']['no_auth'] = true;
$config['mongo_db']['external-portal']['hostname'] = 'localhost';
$config['mongo_db']['external-portal']['port'] = '27017';
$config['mongo_db']['external-portal']['username'] = '';
$config['mongo_db']['external-portal']['password'] = '';
$config['mongo_db']['external-portal']['database'] = 'external-portal';
$config['mongo_db']['external-portal']['db_debug'] = TRUE;
$config['mongo_db']['external-portal']['return_as'] = 'object';
$config['mongo_db']['external-portal']['write_concerns'] = (int)1;
$config['mongo_db']['external-portal']['journal'] = TRUE;
$config['mongo_db']['external-portal']['read_preference'] = 'primary';
$config['mongo_db']['external-portal']['read_concern'] = 'local'; //'local', 'majority' or 'linearizable'
$config['mongo_db']['external-portal']['legacy_support'] = TRUE;


////DB configuration for rtps portal
$config['mongo_db']['site']['no_auth'] = true;
$config['mongo_db']['site']['hostname'] = 'localhost';
$config['mongo_db']['site']['port'] = '27017';
$config['mongo_db']['site']['username'] = '';
$config['mongo_db']['site']['password'] = '';
$config['mongo_db']['site']['database'] = 'portal';
$config['mongo_db']['site']['db_debug'] = TRUE;
$config['mongo_db']['site']['return_as'] = 'object';
$config['mongo_db']['site']['write_concerns'] = (int)1;
$config['mongo_db']['site']['journal'] = TRUE;
$config['mongo_db']['site']['read_preference'] = 'primary';
$config['mongo_db']['site']['read_concern'] = 'local'; 
//'local', 'majority' or 'linearizable'
$config['mongo_db']['portal']['legacy_support'] = TRUE;


////DB configuration for rtps appeal
$config['mongo_db']['appeal']['no_auth'] = true;
$config['mongo_db']['appeal']['hostname'] = 'localhost';
$config['mongo_db']['appeal']['port'] = '27017';
$config['mongo_db']['appeal']['username'] = '';
$config['mongo_db']['appeal']['password'] = '';
$config['mongo_db']['appeal']['database'] = 'appeal';
$config['mongo_db']['appeal']['db_debug'] = TRUE;
$config['mongo_db']['appeal']['return_as'] = 'object';
$config['mongo_db']['appeal']['write_concerns'] = (int)1;
$config['mongo_db']['appeal']['journal'] = TRUE;
$config['mongo_db']['appeal']['read_preference'] = 'primary';
$config['mongo_db']['appeal']['read_concern'] = 'local'; 
//'local', 'majority' or 'linearizable'
$config['mongo_db']['appeal']['legacy_support'] = TRUE;

// pre($config);

$config['mongo_db']['expr']['no_auth'] = true;
$config['mongo_db']['expr']['hostname'] = 'localhost';
$config['mongo_db']['expr']['port'] = '27017';
$config['mongo_db']['expr']['username'] = '';
$config['mongo_db']['expr']['password'] = '';
$config['mongo_db']['expr']['database'] = 'iservices';
$config['mongo_db']['expr']['db_debug'] = TRUE;
$config['mongo_db']['expr']['return_as'] = 'object';
$config['mongo_db']['expr']['write_concerns'] = (int)1;
$config['mongo_db']['expr']['journal'] = TRUE;
$config['mongo_db']['expr']['read_preference'] = 'primary';
$config['mongo_db']['expr']['read_concern'] = 'local'; //'local', 'majority' or 'linearizable'
$config['mongo_db']['expr']['legacy_support'] = TRUE;

// for service plus
$config['mongo_db']['service_plus']['no_auth'] = true;
$config['mongo_db']['service_plus']['hostname'] = 'localhost';
$config['mongo_db']['service_plus']['port'] = '27017';
$config['mongo_db']['service_plus']['username'] = '';
$config['mongo_db']['service_plus']['password'] = '';
$config['mongo_db']['service_plus']['database'] = 'service_plus';
$config['mongo_db']['service_plus']['db_debug'] = TRUE;
$config['mongo_db']['service_plus']['return_as'] = 'object';
$config['mongo_db']['service_plus']['write_concerns'] = (int)1;
$config['mongo_db']['service_plus']['journal'] = TRUE;
$config['mongo_db']['service_plus']['read_preference'] = 'primary';
$config['mongo_db']['service_plus']['read_concern'] = 'local'; //'local', 'majority' or 'linearizable'
$config['mongo_db']['service_plus']['legacy_support'] = TRUE;
// var_dump($config);die;
/* End of file database.php */
/* Location: ./application/config/database.php */
