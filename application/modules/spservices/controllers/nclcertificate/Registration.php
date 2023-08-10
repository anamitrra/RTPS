<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {

    private $serviceName = "Application for Non Creamy Layer Certificate";
    Private $serviceId = "INCMCER";

    public function __construct() {
        parent::__construct();
        $this->load->model('nclcertificate/registration_model');
        $this->load->helper("cifileupload");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
          }else{
            $this->slug = "user";
          }

    }//End of __construct()

  

    public function index($obj_id=null) {

       
        $data=array("pageTitle" => "Application for Non Creamy Layer Certificate");
        $filter = array("_id" =>new ObjectId($obj_id), "status" => "DRAFT");
        $data["dbrow"] = $this->registration_model->get_row($filter);
        $data['usser_type']=$this->slug;
       
        $this->load->view('includes/frontend/header');
        $this->load->view('nclcertificate/registration',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()
    
    function createcaptcha() {
        $captchaDir = "storage/captcha/";
        array_map('unlink', glob("$captchaDir*.jpg"));

        $this->load->helper('captcha');
        $config = array(
            'img_path' => './storage/captcha/',
            'img_url' => base_url('storage/captcha/'),
            'font_path' => APPPATH.'sys/fonts/texb.ttf',
            'img_width' => '150',
            'img_height' => 30,
            'expiration' => 7200,
            'word_length' => 6,
            'font_size' => 16,
            'img_id' => 'capimg',
            'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(94, 20, 38),
                'text' => array(0, 0, 0),
                'grid' => array(178, 184, 194)
            )
        );
        $captcha = create_captcha($config);        
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);
        echo $captcha['image'];
    }//End of createcaptcha()
    
    function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->registered_deed_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        }//End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-CRCPY/" . $date."/" .$number;
        return $str;
    }//End of generateID()  

}//End of Castecertificate
