<?php defined('BASEPATH') or exit('No direct script access allowed');
// ini_set('session.cookie_samesite', 'Lax');
/**
 * CodeIgniter-HMVC
 *
 * @package    CodeIgniter-HMVC
 * @author     N3Cr0N (N3Cr0N@list.ru)
 * @copyright  2019 N3Cr0N
 * @license    https://opensource.org/licenses/MIT  MIT License
 * @link       <URI> (description)
 * @version    GIT: $Id$
 * @since      Version 0.0.1
 * @filesource
 *
 */

class MY_Controller extends MX_Controller
{
    //
    public $CI;

    /**
     * An array of variables to be passed through to the
     * view, layout,....
     */
    protected $data = array();

    /**
     * [__construct description]
     *
     * @method __construct
     */
    public function __construct()
    {
        // To inherit directly the attributes of the parent class.
        parent::__construct();

        // This function returns the main CodeIgniter object.
        // Normally, to call any of the available CodeIgniter object or pre defined library classes then you need to declare.
        $CI = &get_instance();

        // Copyright year calculation for the footer
        $begin = 2019;
        $end =  date("Y");
        $date = "$begin - $end";

        // Copyright
        $this->data['copyright'] = $date;

        // restrict methods
        if (!in_array($this->input->method(TRUE), ['GET', 'POST', 'HEAD'])) {
            //            pre($this->response('test',405));
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(405);
            //                ->set_output(json_encode($json_data))
        }
        $dir = strval($CI->uri->segment(1, 0));
        // $cookie_options = array(
        //         'expires' => time() + 60*60,
        //         'path' => '/',
        //         // 'domain' => 'localhost', // leading dot for compatibility or use subdomain
        //         'secure' => true, // or false
        //         'httponly' => true, // or false
        //         'samesite' => 'Lax' // None || Lax || Strict
        //       );

        //       setcookie('samesite-rtps', '1', $cookie_options);

        //        setcookie('samesite-rtps', '1', 0, '/; samesite=strict');
        //         header("Set-Cookie: samesite-rtps=1; expires=0; path=/; SameSite=Strict");
        //        header("Set-Cookie: samesite-rtps=1; path=/$1; SameSite=none; secure");
        header("X-Frame-Options: DENY");
        header('X-Content-Type-Options: nosniff');
        header("X-XSS-Protection: 1; mode=block");
        // header("Content-Security-Policy:  default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://www.google.com");
        //        header("Content-Security-Policy: default-src 'self' https://cdnjs.cloudflare.com http://www.w3.org");
        header("Referrer-Policy: origin-when-cross-origin");
    }


}

// Backend controller
require_once(APPPATH . 'core/Rtps_Controller.php');

// Frontend controller
require_once(APPPATH . 'core/Frontend_Controller.php');
require_once(APPPATH . 'core/Site_Controller.php');
require_once(APPPATH . 'core/External_Controller.php');
