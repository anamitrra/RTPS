<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Frontend
{

    private $serviceName = "Application form for User Registration";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mcc_users/districts_model');
        $this->load->model('mcc_users/circles_model');
        $this->load->model('mcc_users/registrations_model');
        $this->load->model('mcc_users/Office_users_model');
        $this->load->model('mcc_users/Office_user_roles_model');
        $this->load->model('mcc_users/Office_user_designation_model');
        $this->load->model('mcc_users/Office_user_manage_model');
        $this->load->model('offline/Office_list_model');
        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');

        $this->load->helper('string');

        $this->load->library('form_validation');
        $this->load->helper('form');
    } //End of __construct()

    public function index($objId = null)
    {
        // pre('ok');
        $data = array(
            "service_name" => $this->serviceName
        );
        // $dbRow = $this->Office_users_model->get_by_doc_id($objId);
        $data['user_roles'] = (array)$this->Office_user_roles_model->getAllUserRoles();
        $data['user_designation'] = (array)$this->Office_user_designation_model->getAllUserDesignation();
        $data['office_list'] = $this->Office_list_model->get_offices();

        $this->load->view('includes/mcc_users/header');
        $this->load->view('mcc_users/user_registration', $data);
        $this->load->view('includes/mcc_users/footer');
    } //End of index()

    public function reset()
    {
        redirect('spservices/mcc/user-registration', 'refresh');
    }

    public function submit()
    {
        // pre($this->input->post());
        $this->load->library('form_validation');
        $this->form_validation->set_rules('applicant_name', 'Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('contact_number', 'Mobile Number', 'trim|required|exact_length[10]xss_clean|strip_tags');
        $this->form_validation->set_rules('emailid', 'Email', 'trim|strip_tags');
        // $this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean|strip_tags');        
        $this->form_validation->set_rules('pa_district_name', 'District', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('pa_circle', 'Circle', 'trim|required|xss_clean|strip_tags');

        //check if offline service user 
        if ($this->input->post('role_slug_name') === "OFFSU") {
            $this->form_validation->set_rules('office_id', 'Offline', 'trim|required|xss_clean|strip_tags');
        }
        // Field conditional render
        $is_dps = $this->input->post("isdps") ?? '';
        // if ($this->input->post("isdps") == "1") {
        if ($is_dps == "1") {
            $empty_cir_name_id = true;
        } else {
            if ($this->input->post("user_role") == "Dealing Assistant") {
                $empty_cir_name_id = true;
            } else {
                $empty_cir_name_id = false;
                $this->form_validation->set_rules('pa_circle', 'Circle', 'trim|required|xss_clean|strip_tags');
            }
        }

        $this->form_validation->set_rules('user_role', 'User Designation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mobile_verify_status', 'Mobile verify status', 'trim|required|greater_than_equal_to[1]|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('office_user_creation_error', '' . validation_errors());
            $this->index(null);
        } elseif (($this->input->post("mobile_verify_status") == 0) && ($this->input->post("contact_number") !== $this->session->verified_mobile_numbers[0])) {
            $this->session->set_flashdata('error', 'Mobile no. ' . $this->input->post("contact_number") . ' does not matched with the OTP verified number : ' . $this->session->verified_mobile_numbers[0]);
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } else {
            $district_name = $this->input->post("pa_district_name");
            $district_data = (array)$this->districts_model->get_rows(array("district_name" => $district_name));
            $dist_id = ($district_data[0]->district_id);
            $circle_name = $this->input->post("pa_circle");
            $circle_id = (array)$this->circles_model->getCircleId($circle_name);
            $user_role = $this->input->post("user_role");
            // $user_role_id =  (array)$this->Office_user_roles_model->getUserRoleId($user_role);
            if ($is_dps == "1") {
                $role_name = "Designated Public Servant";
                $role_slug_name = "DPS";
            } else {
                $role_name = $user_role;
                $role_slug_name = $this->input->post("role_slug_name");
            }

            // check exist Phone number
            $getUserPhoneNumber =  (array)$this->Office_users_model->getUserPhoneNumber($this->input->post("contact_number"));
            if ($getUserPhoneNumber) {
                for ($i = 0; $i < count($getUserPhoneNumber); $i++) {
                    if ($getUserPhoneNumber[$i]->is_active == 1) {
                        // $this->session->set_flashdata('error','This phone number is already active');
                        // redirect(base_url('spservices/office_user/registration'));
                        $this->session->set_flashdata('office_user_creation_error', 'This phone number is in active state.. please try a different phone number');
                        $this->index(null);
                        return;
                    };
                }
            }

            $data = array(
                'name' => $this->input->post("applicant_name"),
                'mobile' => $this->input->post("contact_number"),
                'email' => $this->input->post("emailid"),
                'district_name' => $district_name,
                'district_id' => (string)$dist_id,
                'circle_name' => $empty_cir_name_id ? "" : $circle_name,
                'circle_id' => $empty_cir_name_id ? "" : (string)$circle_id[0]->circle_id,
                'designation' => $this->input->post("user_role"),
                'user_role' => $role_name,
                'user_role_id' => $this->input->post('user_role_id'),
                'role_slug_name' => $role_slug_name,
                'offline_office_id' => $this->input->post('office_id'),
                'is_active' => 0,
                'mobile_verify_status' => $this->input->post("mobile_verify_status"),
            );
            $insert = $this->Office_users_model->insert($data);
            if ($insert) {
                $this->session->set_flashdata('office_user_creation_success', 'Office user has been successfully created');
                redirect('spservices/mcc/user-registration');
                // redirect(base_url('spservices/mcc_office_user/registration'));
            }
        }
    }

    function check_user($mobile = null)
    {
        if (strlen($mobile) == 10) {
            $this->load->model('mcc_office_user/office_user_manage_model');
            $activeUsers = $this->office_user_manage_model->get_rows(['mobile' => $mobile, 'is_active' => 1]);
            $inActiveUsers = $this->office_user_manage_model->get_rows(['mobile' => $mobile]);
            if ($activeUsers) {
                $resArr = array("status" => false, "message" => "User already exist and active against this mobile number.Only one user can be active against same mobile number");
            } elseif ($inActiveUsers) {
                $resArr = array("status" => false, "message" => "User already exist but not yet active against this mobile number.Only one user can be active against same mobile number");
            } else {
                $resArr = array("status" => true, "message" => "Congratulation!, You can register against this mobile number.");
            }
        } else {
            $resArr = array("status" => false, "message" => "Invalid mobile number");
        } //End of if else
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($resArr));
    } //End of check_user()

    function get_circles()
    {

        $input_name = $this->input->post("input_name");
        $fld_name = $this->input->post("fld_name");
        $dist_name = $this->input->post("fld_value");

        $fld_value = $this->districts_model->get_rows(array("district_name" => $dist_name));
        $array = json_decode(json_encode($fld_value), true);
        $fld_value = ($array[0]["district_id"]);


        $circles = $this->circles_model->get_rows(array($fld_name => $fld_value)); ?>
        <select name="<?= $input_name ?>" id="<?= $input_name ?>" class="form-control">
            <option value="">Select a circle </option>
            <?php if ($circles) {
                foreach ($circles as $circle) {
                    echo '<option value="' . $circle->circle_name . '">' . $circle->circle_name . '</option>';
                } //End of foreach()
            } //End of if 
            ?>
        </select><?php
                } //End of get_circles()






            }//End of Registration
