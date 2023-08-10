<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Profile extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mcc_users/districts_model');
        $this->load->model('mcc_users/circles_model');
        $this->load->model('mcc_users/registrations_model');
        $this->load->model('mcc_users/Office_users_model');
        $this->load->model('mcc_users/Office_user_manage_model');
        $this->load->model('mcc_users/Office_user_roles_model');
        $this->load->model('mcc_users/Office_user_designation_model');
        $this->load->model('mcc_users/Office_admin_model');
        $this->load->model('mcc_users/Minoritycertificatie_model');
        $this->load->model('mcc_users/Transfer_log');
        $this->load->model('mcc_users/application_model');
        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->library('session');
        $this->load->library('form_validation');
        $admin = $this->session->userdata('admin');
        if (empty($admin)) {
            $this->session->set_flashdata('msg', 'Your session has been expired!');
            redirect(base_url() . 'spservices/mcc/admin-login');
        }
    } //End of __construct()

    public function index()
    {
        $id = $this->session->userdata('admin')['admin_id']->{'$id'};
        $role = $this->session->userdata('admin')['role'];
        $data['admin_details'] = (array)$this->Office_admin_model->getAdminDetails($id);
        $data['name'] = $this->session->userdata('admin')['name'];
        $data['mobile_verify'] = 0;
        if ($role == 'SA') {
            $viewPage = 'mcc_users/admin_home';
        } else {
            $data['mobile_verify'] = 1;
            $data['counts'] = (array)$this->application_model->get_application_count();
            $viewPage = 'mcc_users/district_dashboard';
        }
        $this->load->view('includes/mcc_users/dashb/header', $data);
        $this->load->view($viewPage, $data);
        $this->load->view('includes/mcc_users/dashb/footer');
    }

    public function profile()
    {
        $id = $this->session->userdata('admin')['admin_id']->{'$id'};
        $role = $this->session->userdata('admin')['role'];
        $data['admin_details'] = (array)$this->Office_admin_model->getAdminDetails($id);
        $data['name'] = $this->session->userdata('admin')['name'];
        $url = 'mcc_users/admin_home';
        $this->load->view('includes/mcc_users/dashb/header', $data);
        $this->load->view($url, $data);
        $this->load->view('includes/mcc_users/dashb/footer');
    }

    public function mobile_verify()
    {
        $id = $this->session->userdata('admin')['admin_id']->{'$id'};
        $role = $this->session->userdata('admin')['role'];
        $data['admin_details'] = (array)$this->Office_admin_model->getAdminDetails($id);
        $data['name'] = $this->session->userdata('admin')['name'];
        $url = 'mcc_users/mobile_verify';
        $this->load->view('includes/mcc_users/dashb/header', $data);
        $this->load->view($url, $data);
        $this->load->view('includes/mcc_users/dashb/footer');
    }
}
