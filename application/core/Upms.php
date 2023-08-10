<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Upms extends MY_Controller {
    function __construct() {
        parent::__construct();    
        //echo "I am inside Alom<br />";
    }//End of __construct()
        
    function isloggedin($right = NULL){
        if($this->session->upms_login_status){           
            if(strlen($right)){
                if(isRight($right)) {
                    //$this->session->set_flashdata("accessMsg", "You have the permission : ".$right);
                } else {
                    $this->session->set_flashdata("accessMsg", "You do not have the permission to access this function!!!");
                    redirect(site_url('upms'));                
                }//End of if else 
            } else {
               //$this->session->set_flashdata("accessMsg", "You have the permission to access this function");
            }//End of if else
	} else {
            $this->session->set_flashdata("accessMsg", "Session has been Expired!");
            redirect(site_url("spservices/upms/login"), "refresh");
        }//End of if else
    }//End of isloggedin()
        
    function isdev(){
        if($this->session->upms_login_status){
            if($this->session->upms_user_type != 1){              
                $this->session->set_flashdata("accessMsg", "Access not allowed for developer. Please contact your administrator");
                //redirect(site_url(SUPADMIN_DIR."signin/logout")); 
                redirect(site_url("spservices/upms/site"));
            }//End of if else
        } else {
            $this->session->set_userdata('redirect_to', current_url());
            $this->session->set_flashdata("accessMsg", "Session has been Expired!");
            redirect(site_url("spservices/upms/login"), "refresh");
        }//End of if else
    }//End of isdev()
}//End of Upms