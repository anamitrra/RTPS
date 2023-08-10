<?php

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Userregistration extends Frontend {

    public function __construct() {
        parent::__construct();
        $this->load->model('upms/depts_model');
        $this->load->model('upms/services_model');
        $this->load->model('upms/offices_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/districts_model');
        $this->load->model('upms/users_model');
        $this->load->model('zones_model');
        $this->load->model('zonecircles_model');
        $this->load->config('upms_config');
    }//End of __construct()

    public function index() {
        $this->load->view('includes/frontend/header');
        $this->load->view('user_registration_form');
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function submit() {
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules('dept_info', 'Department', 'required');
        $this->form_validation->set_rules('user_services[]', 'Service', 'required');
        $this->form_validation->set_rules('user_roles', 'Role', 'required');
        $this->form_validation->set_rules('user_fullname', 'Name', 'required|max_length[255]');
        $this->form_validation->set_rules('mobile_number', 'Mobile', 'required|integer|exact_length[10]');
        $this->form_validation->set_rules('email_id', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('login_username', 'User ID', 'required|alpha_dash|max_length[20]');
        $this->form_validation->set_rules('login_password', 'Login password', 'required|alpha_dash|max_length[20]');
        $this->form_validation->set_rules('login_password_conf', 'Confirm password', 'required|alpha_dash|max_length[20]|matches[login_password]');

        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } else {
            $user_services = array();
            $userServices = $this->input->post("user_services");
            if ($userServices && count($userServices)) {
                foreach ($userServices as $userService) {
                    $user_services[] = json_decode(html_entity_decode($userService));
                } //End of foreach()
            } //End of if
            $user_roles = json_decode(html_entity_decode($this->input->post("user_roles")));
            if(count((array)$user_roles)) {
                $user_levels = array(
                    "level_no" => $user_roles->level_no,
                    "level_name" => $user_roles->level_name
                );
            } else {
                $user_levels = array();
            }//End of if else
            
            $login_username = $this->input->post("login_username");
            $filter = array("login_username" => $login_username);
            if (strlen($objId) == 0) {
                $dbRow = $this->users_model->get_row($filter);
            } else {
                $dbRow = false;
            }//End of if else

            if ((strlen($objId) == 0) && $dbRow) {
                $this->session->set_flashdata('error', 'Username already exists in the selected service');
                $this->index();
            } else {
                $data = array(
                    "user_services" => $user_services,
                    "user_roles" => $user_roles,
                    "user_levels" => $user_levels,
                    "district_info" => json_decode(html_entity_decode($this->input->post("district_info"))),
                    "dept_info" => json_decode(html_entity_decode($this->input->post("dept_info"))),
                    "zone_info" => json_decode(html_entity_decode($this->input->post("zone_info"))),
                    "zone_circle" => $this->input->post("zone_circle"),
                    "user_fullname" => $this->input->post("user_fullname"),
                    "mobile_number" => $this->input->post("mobile_number"),
                    "email_id" => $this->input->post("email_id"),
                    "user_types" => array("utype_id" => 3, "utype_name" => "Staff/Dept user"),
                    "registered_by" => 'SELF',
                    "registration_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "status" => 1
                );
                //echo '<pre>'; var_dump($data); '</pre>'; die;
                if (strlen($objId)) {
                    $this->users_model->update_where(['_id' => new ObjectId($objId)], $data);
                    $this->session->set_flashdata('success', 'User has been successfully updated');
                } else {
                    $login_password = $this->input->post("login_password");
                    $salt = uniqid("", true);
                    $algo = "6";
                    $rounds = "5050";
                    $cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;
                    $hashedPassword = crypt($login_password, $cryptSalt);

                    $data["login_username"] = $login_username;
                    $data["login_password"] = $hashedPassword;
                    $this->users_model->insert($data);
                    $this->session->set_flashdata('success', 'Your account has been successfully created.');
                }//End of if else
                redirect('spservices/userregistration');
            }//End of if else
        }//End of if else
    }//End of submit()
        
    function get_services() {
        $dept_code=$this->input->post("dept_code");
        $rows = $this->services_model->get_rows(array("dept_info.dept_code" => $dept_code));
        $data = array();
        foreach($rows as $row) {
            $serviceObj = json_encode(array("service_code"=>$row->service_code, "service_name" => $row->service_name));
            $data[] = array("service_code"=>$row->service_code, "service_name" => $row->service_name, "service_obj" => $serviceObj);            
        }//End of foreach()
        $json_obj = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($json_obj);
    }//End of get_services()
                       
    function get_zonecircles() {
        $zone_code = $this->input->post("zone_code");
        $zonecircleRows = $this->zonecircles_model->get_rows(array("zone_code" => $zone_code));
        echo '<select name="zone_circle" id="zone_circle" class="form-control">';          
            if($zonecircleRows) {
                echo '<option value="">Select zonecircle </option>';
                foreach($zonecircleRows as $zonecircle) {
                    echo "<option value='{$zonecircle->circle_name}'>{$zonecircle->circle_name}</option>"; 
                }//End of foreach()
            } else {
                echo '<option value="">No records found</option>';
            }//End of if
        echo "</select>";
    }//End of get_zonecircles()
                           
    function get_roles() {
        $service_code = $this->input->post("service_code");
        $levels = $this->levels_model->get_rows(array("level_services.service_code"=>$service_code)); ?>                   
        <select name="user_roles" id="user_roles" class="form-control">
            <option value="">Select a role </option>
            <?php if($levels) {
                foreach($levels as $level) {
                    $userRoles = (array)$level->level_roles;
                    $userRoles['level_name'] = $level->level_name;
                    $userRoles['level_no'] = $level->level_no;
                    //$lbl = $level->level_roles->role_name.' of '.$level->level_name.'-'.$level->level_no;
                    $lbl = $level->level_roles->role_name;
                    echo "<option value='".json_encode($userRoles)."'>".$lbl."</option>";
                }//End of foreach()
            }//End of if ?>
        </select><?php
    }//End of get_roles()
                       
    function get_zones() {
        $dept_code = $this->input->post("dept_code");
        $zoneRows = $this->zones_model->get_rows(array("dept_code" => $dept_code));
        echo '<select name="zone_info" id="zone_info" class="form-control">';          
            if($zoneRows) {
                echo '<option value="">Select zone </option>';
                foreach($zoneRows as $zone) {
                    $zoneObj = json_encode(array("zone_code"=>$zone->zone_code, "zone_name" => $zone->zone_name));
                    echo "<option value='{$zoneObj}'>{$zone->zone_name}</option>"; 
                }//End of foreach()
            } else {
                echo '<option value="">No records found</option>';
            }//End of if
        echo "</select>";
    }//End of get_zones()
}
