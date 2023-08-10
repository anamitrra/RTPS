<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Applications extends admin
{
    public function __construct()
    {
        parent::__construct();
        $this->user_type();
        $this->load->model('admin/users_model');
        $this->load->model('admin/districts_model');
        $this->load->model('admin/depts_model');
        $this->load->model('admin/services_model');
        $this->load->model('admin/levels_model');
        $this->load->model('admin/applications_model');
        $this->load->model('admin/transferlog_model');

    } //End of __construct()

    public function index()
    {
        $filter['user_types.utype_id'] = 3;
        if ($this->user_type() == 5) {
            $filter['dept_info.dept_code'] = $this->session->userdata('administrator')['dept_id'];
        }
        $data['users'] = $this->users_model->get_rows($filter);
        $this->load->view('adminviews/includes/header', $data);
        $this->load->view('adminviews/user_list', $data);
        $this->load->view('adminviews/includes/footer');
    } //End of index()

    public function migration()
    {
        $filter['status'] = 1;
        if ($this->user_type() == 5) {
            $filter['dept_code'] = $this->session->userdata('administrator')['dept_id'];
        }
        $data['depts'] = $this->depts_model->get_rows($filter);
        $this->load->view('adminviews/includes/header', $data);
        $this->load->view('adminviews/migrate_applicationview', $data);
        $this->load->view('adminviews/includes/footer');
    } //End of migration()

    public function get_applications()
    {
        $service_data = json_decode(html_entity_decode($this->input->post("service_id")));
        $transfer_frm = json_decode(html_entity_decode($this->input->post("transfer_from")));
        $transfer_to = json_decode(html_entity_decode($this->input->post("transfer_to")));
        $filter = array(
            'service_data.service_id' => $service_data->service_code,
            'current_users.login_username' => $transfer_frm->username,
            'service_data.appl_status' => ['$ne' => 'D']
        );
        $applications = $this->applications_model->get_rows($filter);
        if ($applications) {
            $data['status'] = true;
            $data['applications'] = $applications;
            $data['transfer_frm'] = $transfer_frm->username;
            $data['transfer_to'] = $transfer_to->username;
        } else {
            $data['status'] = false;
            $data['msg'] = 'No applications found.';
        }
        $json_obj = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($json_obj);
    }

    public function process_migration()
    {
        $filter1['login_username'] = $this->input->post('frm');
        $transfer_from = $this->users_model->get_row($filter1);

        $filter2['login_username'] = $this->input->post('to');
        $transfer_to = $this->users_model->get_row($filter2);

        $remarks = $this->input->post('remarks');
        $total_appl = $this->input->post('appl_nos');
        for ($i = 0; $i < count($total_appl); $i++) {
            $this->submit_migrate($total_appl[$i], $transfer_from, $transfer_to, $remarks);
        }
    }

    public function submit_migrate($ref_no, $trs_from, $trs_to, $remarks)
    {
        $dbRow = $this->applications_model->get_row(['service_data.appl_ref_no' => $ref_no]);
        $processing_history = $dbRow->processing_history ?? array();
        $current_users = array(
            "login_username" => $trs_to->login_username,
            "user_role_code" => $trs_to->user_roles->role_code,
            "user_level_no" => $trs_to->user_levels->level_no,
            "user_fullname" => $trs_to->user_fullname
        );

        $loggedInUserData = array(
            "login_username" => $this->session->userdata('administrator')['username'],
            "user_role_code" => "",
            "user_level_no" => "",
            "user_fullname" => $this->session->userdata('administrator')['name']
        );
        $actionTaken = array(
            "action_code" => "RE_ASSIGNED",
            "action_details" => "Application Re-assigned to new user"
        );

        $processing_history[] = array(
            "processed_by" => $loggedInUserData["user_fullname"],
            "action_taken" => $actionTaken["action_details"],
            "remarks" => $remarks,
            "file_uploaded" => "",
            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "processed_by_obj" => $loggedInUserData,
            "action_taken_obj" => $actionTaken,
            "forward_to" => "",
            "backward_to" => "",
            "ifprocessed_by" => json_decode(html_entity_decode($this->input->post("ifprocessed_by"))),
            "process_no" => "",
            "custom_field_values" => ""
        );
        $data = array(
            "current_users" => $current_users,
            "processing_history" => $processing_history
        );

        $transfer_log = [
            "transfer_from" => $trs_from->_id->{'$id'},
            "transfer_to" => $trs_to->_id->{'$id'},
            "application_ref_no" => $ref_no,
            "remarks" => $remarks,
            "transfer_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
        ];
        $this->applications_model->update_where(['service_data.appl_ref_no' => $ref_no], $data);
        $this->transferlog_model->insert($transfer_log);
        $this->session->set_flashdata('flashMsg','Migration has been successfully done.');            
        redirect('spservices/admin/applications/migration');
    }
}
