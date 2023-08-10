<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Users extends Frontend
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
        $this->load->model('mcc_users/Status_history_model');
        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->library('session');
        $admin = $this->session->userdata('admin');
        if (empty($admin)) {
            $this->session->set_flashdata('msg', 'Your session has been expired!');
            redirect(base_url() . 'spservices/mcc/admin-login');
        }
    } //End of __construct()

    // List of office users
    public function index()
    {
        $role = $this->session->userdata('admin')['role'];
        if ($role == 'SA') {
            $district = '';
        } else {
            $district = $this->session->userdata('admin')['district'];
        }
        $data['users'] = (array)$this->Office_user_manage_model->getOfficeUsers($district);
        $data['name'] = $this->session->userdata('admin')['name'];
        $this->load->view("includes/mcc_users/dashb/header", $data, array("pageTitle" => "Dashboard"));
        $this->load->view('mcc_users/user_list', $data);
        $this->load->view('includes/mcc_users/dashb/footer');
    } //End of index()

    // Detail view of office user
    public function view($id)
    {
        $data['user'] = (array)$this->Office_user_manage_model->get_single_office_user($id);
        $data['user_roles'] = (array)$this->Office_user_roles_model->getAllUserRoles();
        $data['user_designation'] = (array)$this->Office_user_designation_model->getAllUserDesignation();
        $data['name'] = $this->session->userdata('admin')['name'];
        $this->load->view('includes/mcc_users/dashb/header', $data);
        $this->load->view('mcc_users/user_view', $data);
        $this->load->view('includes/mcc_users/dashb/footer');
    }

    public function updateUser()
    {
        $obj_id = $this->input->post('objId');
        $remarks = $this->input->post('status_change_remks');
        $user = (array)$this->Office_user_manage_model->get_single_office_user($obj_id);
        $mobile = $user[0]->mobile;
        $is_active = $user[0]->is_active;
        $dbFilter = array(
            'mobile' => $mobile,
            'is_active' => 1,
        );
        $dbRows = $this->Office_user_manage_model->get_rows($dbFilter);
        if ($dbRows) {
            $new_is_active = 0; //Since user already active with same number
            // $flashMsg = 'User having mobile number ' . $mobile . ' has been deactivated.';
            $flashMsg = 'User having mobile number <span class="text-danger">' . $mobile . '</span> have multiple accounts and deactivated the selected one.';
        } else {
            if ($is_active == 1) {
                $new_is_active = 0;
                $flashMsg = 'User having mobile number <span class="text-danger">' . $mobile . '</span> has been deactivated successfully.';
            } else {
                $new_is_active = 1;
                $flashMsg = 'User having mobile number <span class="text-danger">' . $mobile . '</span> has been successfully activated.';
            } //End of if else
        } //End of if else
        $changeData = array(
            'user_id' => $obj_id,
            'mobile_number' => $mobile,
            'remarks' => $remarks,
            'status' => $new_is_active,
            'txn_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
        );
        $data = array('is_active' => $new_is_active);
        $this->Office_user_manage_model->update_where(['_id' => new ObjectId($obj_id)], $data);
        $this->Status_history_model->insert($changeData);
        $this->session->set_flashdata("flashMsg", $flashMsg);
        redirect(base_url() . 'spservices/mcc_users/users');
    } //End of updateUser()
}