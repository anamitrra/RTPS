<?php defined('BASEPATH') or exit('No direct script access allowed');
class Admin extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->isvaliduser();
    }

    public function user_type()
    {
        if ($this->session->userdata('administrator')) {
            return $this->session->userdata('administrator')['user_type'];
        } else {
            $this->session->set_userdata('redirect_to', current_url());
            $this->session->set_flashdata("er_msg", "Session has been Expired!");
            redirect(site_url("spservices/admin/login"), "refresh");
        } //End of if else
    } //End of user_type()

    function isvaliduser()
    {
        if ($this->session->userdata('administrator')) {
            if ($this->session->userdata('administrator')['is_administrator']) {
                if($this->user_type() == 4){
                    $login_label = 'State Administrator';
                } else if($this->user_type() == 5){
                    $dept = $this->session->userdata('administrator')['dept_name'];
                    $login_label = 'Department Administrator (' . $dept . ')';
                }
                $this->session->set_userdata('login_label', $login_label);
            } else {
                $this->session->set_flashdata("er_msg", "You are not authorized to access.");
                redirect(site_url("spservices/admin/login"), "refresh");
            }
        } else {
            $this->session->set_flashdata("er_msg", "You are not authorized to access.");
            redirect(site_url("spservices/admin/login"), "refresh");
        }
    } //End of isvaliduser()
}
