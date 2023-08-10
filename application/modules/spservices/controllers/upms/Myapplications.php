<?php

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Myapplications extends Upms
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
        $this->load->helper("appstatus");
    } //End of __construct()

    public function index()
    {
        $filter = array(
            "current_users.login_username" => $this->session->loggedin_login_username,
            "service_data.service_id" => array('$in' => $this->session->loggedin_user_service_code),
        );
        $additional_role_codes = $this->session->additional_role_codes ?? array();
        if (count($additional_role_codes)) {
            $filter['current_users.user_level_no'] = $this->session->loggedin_user_level_no??0;
        }
        $myapplications = $this->applications_model->get_rows($filter);
        //echo '<pre>'; var_dump($myapplications); '</pre>'; die;
        $data['myapplications'] = $myapplications;
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : My applications", "page" => "my_applications"]);
        $this->load->view('upms/myapplications_view', $data);
        $this->load->view('upms/includes/footer');
    } //End of index()

    public function preview($objId = null)
    {
        $dbRow = $this->applications_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array("form_row" => $dbRow);
            $this->load->view('upms/includes/header', ["header_title" => "UPMS : Application Preview"]);
            $this->load->view('upms/myapplication_preview', $data);
            $this->load->view('upms/includes/footer');
        } else {
            $this->session->set_flashdata('flashMsg', 'Records does not exist');
            redirect('spservices/upms/myapplications');
        } //End of if else
    } //End of preview()

    public function process($objId = null)
    {
        $dbRow = $this->applications_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $appl_status = $dbRow->service_data->appl_status;
            if (in_array($appl_status, array("D", "R"))) {
                $this->session->set_flashdata('flashMsg', 'Application status already in ' . $appl_status);
                redirect('spservices/upms/myapplications');
            } else {
                $con_reg_pwdb1 = $this->config->item("con_reg_pwdb1");
                $acmrprcmd = $this->config->item("acmrprcmd");
                $acmrCodes = $this->config->item('acmr_codes');
                $conRegCodes = $this->config->item('con_reg_codes');
                $objId = $dbRow->_id->{'$id'};
                $current_status = $dbRow->status ?? '';

                $data = array(
                    "form_row" => $dbRow,
                    "user_row" => $this->users_model->get_row(array("login_username" => $this->session->loggedin_login_username))
                );
                $serviceId = $dbRow->service_data->service_id ?? '';

                if (in_array($serviceId, $conRegCodes) && ($current_status === 'BACKWARDED')) {
                    redirect('spservices/upms/paymentinfo/user/' . $objId);
                    exit();
                } elseif ($serviceId === $con_reg_pwdb1) {
                    $viewFile = 'upms/customzed/con_reg_pwdb1_view';
                } elseif ($serviceId === $acmrprcmd) {
                    $viewFile = 'upms/customzed/acmrprcmd_view';
                } elseif (in_array($serviceId, $acmrCodes)) {
                    $viewFile = 'upms/customzed/acmr_view';
                } else {
                    $viewFile = 'upms/myapplicationprocessing_view';
                } //End of if else
                $this->load->view('upms/includes/header', ["header_title" => "UPMS : Application processing"]);
                $this->load->view($viewFile, $data);
                $this->load->view('upms/includes/footer');
            } //End of if else
        } else {
            $this->session->set_flashdata('flashMsg', 'Records does not exist');
            redirect('spservices/upms/myapplications');
        } //End of if else
    } //End of process()

    public function process_submit()
    {
        $obj_id = $this->input->post("obj_id");
        $this->form_validation->set_rules('action_taken', 'Action', 'required|max_length[255]');
        $this->form_validation->set_rules('amount_to_pay', 'Amount', 'is_natural_no_zero|less_than[10000]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg', 'Error in inputs : ' . validation_errors());
            $this->process($obj_id);
        } else {
            $amount_to_pay = (int)$this->input->post("amount_to_pay");
            $action_taken = $this->input->post("action_taken");
            $forward_to = json_decode(html_entity_decode($this->input->post("forward_to")));
            $backward_to = json_decode(html_entity_decode($this->input->post("backward_to")));

            $dbrow = $this->applications_model->get_by_doc_id($obj_id);
            $prev_appl_status = $dbrow->service_data->appl_status;
            if (in_array($prev_appl_status, array("D", "R"))) {
                $this->session->set_flashdata('flashMsg', 'Application status already in ' . $prev_appl_status);
                redirect('spservices/upms/myapplications');
                exit;
            } //End of if
            $prev_status = $dbrow->status ?? '';
            $serviceCode = $dbrow->service_data->service_id ?? '';
            $serviceRow = $this->services_model->get_row(array("service_code" => $serviceCode));
            $dscRequired = $serviceRow->dsc_required ?? 'NO';
            $custom_fields = $serviceRow->custom_fields ?? array();
            $serviceMode = $serviceRow->service_mode ?? 'ONLINE';

            $loggedInUserData = array(
                "login_username" => $this->session->loggedin_login_username,
                "user_role_code" => $this->session->loggedin_user_role_code,
                "user_level_no" => $this->session->loggedin_user_level_no,
                "user_fullname" => $this->session->loggedin_user_fullname
            );

            //checking whether login user has multiple roles or not
            $additional_role_codes = $this->session->additional_role_codes ?? array();
            if (count($additional_role_codes)) {
                if ($forward_to) {
                    $forward_to = $this->check_addl_forward_backward($forward_to, $serviceCode, $action_taken);
                }
                if ($backward_to) {
                    $backward_to = $this->check_addl_forward_backward($backward_to, $serviceCode, $action_taken);
                }
            }

            // pre($action_taken);
            if ($action_taken === 'FORWARD') {
                $current_users = $forward_to;
                $processDirection = 'FORWARD';
                $actionDetails = "Forwarded to " . $forward_to->user_fullname;
                $status = "FORWARDED";
                $appl_status = "AF";
            } elseif ($action_taken === 'BACKWARD') {
                $current_users = $backward_to;
                $processDirection = 'REVERSE';
                $actionDetails = "Sent back to " . $backward_to->user_fullname;
                $status = "BACKWARDED";
                $appl_status = "UP";
            } elseif ($action_taken === 'QUERY') {
                $current_users = $loggedInUserData;
                $processDirection = $dbrow->process_direction ?? 'FORWARD';
                $actionDetails = "Query to Applicant";
                $status = "QUERY_ARISE";
                $appl_status = $amount_to_pay ? "FRS" : "QS";
            } elseif ($action_taken === 'QUERY_PAYMENT') {
                $current_users = $loggedInUserData;
                $processDirection = $dbrow->process_direction ?? 'FORWARD';
                $actionDetails = "Payment query to Applicant";
                $status = "QUERY_PAYMENT_ARISE";
                $appl_status = "FRS";
            } elseif ($action_taken === 'GENERATE_CERTIFICATE') {
                $current_users = $loggedInUserData;
                // pre($prev_appl_status);
                $processDirection = $dbrow->process_direction ?? 'FORWARD';
                if ($dscRequired == "YES") {
                    $actionDetails = "Certificate generate initiated";
                    $status = $prev_status;
                    $appl_status = $prev_appl_status;
                } else {
                    $actionDetails = "Certificate generated";
                    $status = "DELIVERED";
                    $appl_status = "D";
                } //End of if else
            } elseif ($action_taken === 'REJECT') {
                $current_users = $loggedInUserData;
                $processDirection = $dbrow->process_direction ?? 'FORWARD';
                $actionDetails = "Application rejected";
                $status = "REJECTED";
                $appl_status = "R";
            } elseif ($action_taken === 'APPROVE') {
                $current_users = $loggedInUserData;
                $processDirection = $dbrow->process_direction ?? 'FORWARD';
                $actionDetails = "Application approved";
                $status = "APPROVED";
                $appl_status = "AA";
            } else {
                $current_users = $loggedInUserData;
                $processDirection = $dbrow->process_direction ?? 'FORWARD';
                $actionDetails = $action_taken;
                $status = "UNDER_PROCESSING";
                $appl_status = "UP";
            } //End of if else
            $actionTaken = array(
                "action_code" => $action_taken,
                "action_details" => $actionDetails
            );
            $processing_history = $dbrow->processing_history ?? array();
            $processNo = count($processing_history) + 1;
            if (isset($_FILES["input_file"])) {
                if (is_uploaded_file($_FILES['input_file']['tmp_name'])) {
                    $this->load->helper("cifileupload");
                    $res = cifileupload("input_file");
                    $fileUploaded = ($res["upload_status"]) ? $res["uploaded_path"] : null;
                } else {
                    $fileUploaded = null;
                } //End of if else
            } else {
                $fileUploaded = null;
            } //End of if else      

            $customFields = array();
            if (count($custom_fields)) {
                foreach ($custom_fields as $custom_field) {
                    $customFields[] = array(
                        "field_level" => $custom_field->field_level,
                        "field_name" => $custom_field->field_name,
                        "field_value" => $this->input->post($custom_field->field_name)
                    );
                } //End of foreach()
            } //End of if          

            $processing_history[] = array(
                "processed_by" => $loggedInUserData["user_fullname"],
                "action_taken" => $actionTaken["action_details"],
                "remarks" => $this->input->post("remarks"),
                "file_uploaded" => $fileUploaded,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "processed_by_obj" => $loggedInUserData,
                "action_taken_obj" => $actionTaken,
                "forward_to" => $forward_to,
                "backward_to" => $backward_to,
                "ifprocessed_by" => json_decode(html_entity_decode($this->input->post("ifprocessed_by"))),
                "process_no" => $processNo,
                "custom_field_values" => $customFields
            );

            $data = array(
                "process_no" => $processNo,
                "process_direction" => $processDirection, //FORWARD or REVERSE
                "current_users" => $current_users,
                "status" => $status,
                "service_data.appl_status" => $appl_status,
                "processing_history" => $processing_history
            );

            if ($amount_to_pay) {
                $data["amount_to_pay"] = $amount_to_pay;
            } //End of if
            $booking_date = $this->input->post("booking_date");
            $time_slot = $this->input->post("time_slot");
            if (strlen($booking_date) && strlen($booking_date)) {
                $data["form_data.booking_date"] = $booking_date;
                $data["form_data.time_slot"] = $time_slot;
            } //End of if

            //certificate update for Offline service
            if (($action_taken === 'APPROVE') && ($serviceMode === 'OFFLINE') && strlen($fileUploaded)) {
                $data["form_data.certificate"] = $fileUploaded;
                $data["status"] = "DELIVERED";
            } //End of if            
            //echo '<pre>'; var_dump($data); '</pre>'; die;
            if (($action_taken === 'GENERATE_CERTIFICATE') && ($dscRequired == "YES")) {
                $base64_rtps_trans_id = base64_encode($dbrow->service_data->appl_ref_no);
                //Please make sure to updated status, service_data.appl_status and send sms
                redirect('spservices/dsign/' . $base64_rtps_trans_id);
            } else if (($action_taken === 'GENERATE_CERTIFICATE') && ($dscRequired == "NO")) {
                $base64_rtps_trans_id = base64_encode($dbrow->service_data->appl_ref_no);
                //Please make sure to updated status, service_data.appl_status and send sms
                redirect('spservices/without-dsign/' . $base64_rtps_trans_id);
            } else {
                $this->applications_model->update_where(['_id' => new ObjectId($obj_id)], $data);
                $this->sendingSMS($status, $dbrow);
                $this->session->set_flashdata('flashMsg', 'Data has been successfully updated');
                redirect('spservices/upms/myapplications/');
            } //End of if else
        } //End of if else
    } //End of process_submit()

    private function sendingSMS($status, $dbrow)
    {
        $this->load->helper('smsprovider');
        if (($status === 'QUERY_ARISE') || ($status === 'QUERY_PAYMENT_ARISE')) {
            $smsType = 'query';
        } elseif ($status === 'REJECTED') {
            $smsType = 'rejection';
        } elseif ($status === 'DELIVERED') {
            $smsType = 'delivery';
        } else {
            $smsType = null;
        } //End of if else

        $mob = $dbrow->form_data->mobile ?? '';
        $mobNo = $dbrow->form_data->mobile_number ?? '';
        if (strlen($mob)) {
            $mobileNumber = (int)$mob;
        } elseif (strlen($mobNo)) {
            $mobileNumber = (int)$mobNo;
        } else {
            $mobileNumber = null;
        } //End of if else

        $submission_date = $dbrow->service_data->submission_date ?? '';
        $createdAtService = $dbrow->service_data->created_at ?? '';
        $createdAtForm = $dbrow->form_data->created_at ?? '';
        if (strlen($submission_date)) {
            $submissionDate = format_mongo_date($submission_date, 'd-m-Y h:i a');
        } elseif (strlen($createdAtService)) {
            $submissionDate = format_mongo_date($createdAtService, 'd-m-Y h:i a');
        } elseif (strlen($createdAtForm)) {
            $submissionDate = format_mongo_date($createdAtForm, 'd-m-Y h:i a');
        } else {
            $submissionDate = date('d-m-Y h:i a');
        } //End of if else

        $applicantName = $dbrow->form_data->applicant_name ?? '';
        $serviceName = $dbrow->service_data->service_name ?? '';
        $refNo = $dbrow->service_data->appl_ref_no ?? '';
        if (strlen($smsType) && strlen($mobileNumber) == 10) {
            $sms = array(
                "mobile" => $mobileNumber,
                "applicant_name" => $applicantName,
                "service_name" => $serviceName,
                "submission_date" => $submissionDate,
                "app_ref_no" => $refNo,
                "rtps_trans_id" => $refNo
            );
            sms_provider($smsType, $sms);
        } //End of if
    } //End of sendingSMS()

    // Start added by Abhijit
    public function check_addl_forward_backward($post_data, $serviceCode, $action_taken)
    {
        $loggedinUser = $this->session->loggedin_login_username ?? '';
        $loggedinUserLevelNo = $this->session->loggedin_user_level_no ?? 0;
        if ($loggedinUser === $post_data->login_username) {
            // return 'same user';
            $levelRow = $this->levels_model->get_row(array("level_services.service_code" => $serviceCode, "level_no" => $loggedinUserLevelNo));
            $backward_levels = $levelRow->backward_levels ?? array();
            $forward_levels = $levelRow->forward_levels ?? array();

            if ($action_taken === 'FORWARD') {
                $forwardLevels = array();
                if (count($forward_levels)) {
                    foreach ($forward_levels as $flvls) {
                        $forwardLevels[] = $flvls->level_no;
                    } //End of foreach()
                } //End of if
                $lvl_no = $this->findImmediateLargerValue($forwardLevels, $loggedinUserLevelNo);
            } elseif ($action_taken === 'BACKWARD') {
                $backwardLevels = array();
                if (count($backward_levels)) {
                    foreach ($backward_levels as $blvls) {
                        $backwardLevels[] = $blvls->level_no;
                    } //End of foreach()
                } //End of if
                $lvl_no = $this->findImmediateSmallerValue($backwardLevels, $loggedinUserLevelNo);
            }

            $levelRow = $this->levels_model->get_row(array("level_services.service_code" => $serviceCode, "level_no" => $lvl_no));
            $next_user = array(
                "login_username" => $post_data->login_username,
                "user_role_code" => $levelRow->level_roles->role_code,
                "user_level_no" => $lvl_no,
                "user_fullname" => $post_data->user_fullname,
            );
            return (object)$next_user;
        } else {
            return (object)$post_data;
        }
    }

    function findImmediateLargerValue($array, $specificValue)
    {
        $immediateLargerValue = null;
        foreach ($array as $value) {
            if ($value > $specificValue) {
                if ($immediateLargerValue === null || $value < $immediateLargerValue) {
                    $immediateLargerValue = $value;
                }
            }
        }
        return $immediateLargerValue;
    }

    function findImmediateSmallerValue($array, $specificValue)
    {
        $immediateSmallerValue = null;

        foreach ($array as $value) {
            if ($value < $specificValue) {
                if ($immediateSmallerValue === null || $value > $immediateSmallerValue) {
                    $immediateSmallerValue = $value;
                }
            }
        }
        return $immediateSmallerValue;
    }
    //End

}//End of Myapplications