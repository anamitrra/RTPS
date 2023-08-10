<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Language extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
    }

    public function index($lang)
    {
        $lang = trim($lang);

        set_custom_cookie('rtps_language', in_array($lang, ['en', 'as', 'bn'], TRUE) ? $lang : 'as');

        // Removing ?lang=xx, if exists, before redirecting
        redirect(

            (strstr($this->agent->referrer(), '?lang=') === false) ? $this->agent->referrer() : 
            explode('?', $this->agent->referrer())[0] 
        );
    }

   
    // public function theme($thm='brn')
    // {
    //     // brn, vlt, grn
    //     $thm = trim($thm);

    //     $cookie = array(
    //         'name'   => 'theme',
    //         'value'  => in_array($thm, ['brn', 'vlt', 'grn'], TRUE) ? $thm : 'brn',
    //         'expire' => time() + 86400 * 30,    // expires in 30 days
    //         'secure' => FALSE,
    //         'httponly' => TRUE,
    //     );
    //     $this->input->set_cookie($cookie);

    //     redirect(
    //         empty($this->agent->referrer()) ? base_url('/site') : $this->agent->referrer()
    //     );
    // }
}
