<?php

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Paymentinfo extends Upms
{

    public function __construct()
    {
        parent::__construct();
        $this->isloggedin();
        $this->load->model('upms/services_model');
        $this->load->model('upms/roles_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/users_model');
        $this->load->model('upms/applications_model');
        $this->load->config('upms_config');
    } //End of __construct()

    public function index()
    {
    } //End of index()

    public function user($objId = null)
    {
        $dbRow = $this->applications_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $appl_status = $dbRow->service_data->appl_status;
            $data = array(
                "form_row" => $dbRow,
                "user_row" => $this->users_model->get_row(array("login_username" => $this->session->loggedin_login_username))
            );
            $this->load->view('upms/includes/header', ["header_title" => "UPMS : application processing"]);
            $this->load->view('upms/customzed/paymentinfo_view', $data);
            $this->load->view('upms/includes/footer');
        } else {
            $this->session->set_flashdata('flashMsg', 'Records does not exist');
            redirect('spservices/upms/myapplications');
        } //End of if else
    } //End of user()

    public function submit_payment()
    {
        $obj_id = $this->input->post("obj_id");

        $this->form_validation->set_rules('bank_name', 'Bank Name', 'required|max_length[255]');
        $this->form_validation->set_rules('acc_number', 'Account Number', 'required');
        $this->form_validation->set_rules('ifsc', 'IFSC', 'required');
        // $this->form_validation->set_rules('validity', 'Validity', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required');

        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg', 'Error in inputs : ' . validation_errors());
            $this->user($obj_id);
        } else {
            if (isset($_FILES["ipo_file"])) {
                if (is_uploaded_file($_FILES['ipo_file']['tmp_name'])) {
                    $this->load->helper("cifileupload");
                    $res = cifileupload("ipo_file");
                    $ipo_copy = ($res["upload_status"]) ? $res["uploaded_path"] : null;
                } else {
                    $ipo_copy = null;
                } //End of if else
            } else {
                $ipo_copy = null;
            } //End of if else  
            if (isset($_FILES["fd_file"])) {
                if (is_uploaded_file($_FILES['fd_file']['tmp_name'])) {
                    $this->load->helper("cifileupload");
                    $res = cifileupload("fd_file");
                    $fd_dd_copy = ($res["upload_status"]) ? $res["uploaded_path"] : null;
                } else {
                    $fd_dd_copy = null;
                } //End of if else
            } else {
                $fd_dd_copy = null;
            } //End of if else  

            $ipo_count = count($this->input->post('ipo_number'));
            for ($i = 0; $i < $ipo_count; $i++) {
                $ipo_data[] = [
                    "ipo_number" => $this->input->post('ipo_number')[$i],
                    "ipo_date" => $this->input->post('ipo_date')[$i],
                    "ipo_amnt" => $this->input->post('ipo_amnt')[$i],
                ];
            }
            $data["form_data.ipo_details"] = $ipo_data;
            $data["form_data.fd_details"] = array(
                'bank_name' => $this->input->post('bank_name'),
                'account_no' => $this->input->post('acc_number'),
                'ifsc' => $this->input->post('ifsc'),
                'validity' => $this->input->post('validity'),
                'amount' => $this->input->post('amount'),
            );

            //For UPMS
            $loggedInUserData = array(
                "login_username" => $this->session->loggedin_login_username,
                "user_role_code" => $this->session->loggedin_user_role_code,
                "user_level_no" => $this->session->loggedin_user_level_no,
                "user_fullname" => $this->session->loggedin_user_fullname
            );
            $dbRow = $this->applications_model->get_by_doc_id($obj_id);
            $forward_to = json_decode(html_entity_decode($this->input->post("forward_to")));
            $processing_history = $dbRow->processing_history ?? array();
            $processNo = count($processing_history) + 1;
            $processing_history[] = array(
                "processed_by" => $this->session->loggedin_login_username,
                "action_taken" => "Forwarded",
                "remarks" => $this->input->post("remarks"),
                "file_uploaded" => null,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "processed_by_obj" => $loggedInUserData,
                "action_taken_obj" => array("action_code" => "FORWARD", "action_details" => "Forwarded"),
                "forward_to" => $forward_to,
                "backward_to" => array(),
                "ifprocessed_by" => array(),
                "process_no" => $processNo
            );

            $data["current_users"] = $forward_to;
            // $data["status"] = "FORWARDED";
            $data["status"] = "APPROVED";

            $data["service_data.appl_status"] = "AF";
            $data["form_data.ipo_copy"] = $ipo_copy;
            $data["form_data.fd_dd_copy"] = $fd_dd_copy;
        
            $data["processing_history"] = $processing_history;

            $this->applications_model->update_where(['_id' => new ObjectId($obj_id)], $data);
            $this->session->set_flashdata('flashMsg', 'Data has been updated successfully.');
            redirect('spservices/upms/myapplications');
        } //End of if else
    } //End of submit_payment()

}
