<?php defined('BASEPATH') or exit('No direct script access allowed');

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

class Frontend extends MY_Controller
{
    //
    public $CI;

    /**
     * An array of variables to be passed through to the
     * view, layout, ....
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
        $this->theme="light";
        $this->rtps_lang = "en";
        $this->getData();
    }
    public function getData(){
        $this->load->helper('cookie');
        if(empty($this->input->cookie('rtps_language',true))){
            $this->rtps_lang='en';
        }else{
            $this->rtps_lang=$this->input->cookie('rtps_language',true);
        }
    }
    public function render_view($page_name = '', $data = '')
        {
            $this->load->model('settings_model');
            $settings = (array) $this->settings_model->get_where(array("name" => "theme"));
            if(!empty($settings)){
                $theme=$settings[0]->active_theme;
            }else{
                $theme="theme1";
            }

            $this->load->view($theme.'/includes/header', array('header_data'=>$this->settings_model->get_settings('header'),"theme"=>$theme));

            
            if(!empty($page_name)){
                $this->load->view($theme."/".$page_name, $data);
            }else{
                $this->load->view($theme.'/index', $data);
            }

            $this->load->view('includes/footer', array('footer_data'=>$this->settings_model->get_settings('footer'),"theme"=>$theme));
        }
}
