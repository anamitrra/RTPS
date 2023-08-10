<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Login extends Frontend {
    public function __construct() {
        parent::__construct();
        $this->load->model('minoritycertificates/registrations_model');
    }//End of __construct()
    
    public function index(){
        
      //  $this->session->set_userdata('redirectTo', 'myId');
        $this->session->set_flashdata('error','Session does not exist, Please login to continue');
        redirect('iservices/login');
    }//End of index()
    
    public function logout() {
        $user_data = $this->session->all_userdata();
        foreach ($user_data as $key => $value) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }
        $this->session->sess_destroy();
        redirect('iservices/login');
    }//End of logout()

}//End of Login
