<?php

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Edituser extends Upms
{

    public function __construct()
    {
        parent::__construct();
        $this->isloggedin();
        $this->load->model('upms/depts_model');
        $this->load->model('upms/services_model');
        $this->load->model('upms/offices_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/districts_model');
        $this->load->model('upms/users_model');
        $this->load->model('zones_model');
        $this->load->model('zonecircles_model');
        $this->load->config('upms_config');
    } //End of __construct()

    public function index($objId = null)
    {
        $data['dbrow'] = $this->users_model->get_by_doc_id($objId);
        // $this->load->model('employment_nonaadhaar/district_model'); //For Employment Exchange only      
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Users"]);
        $this->load->view('upms/user_edit_view', $data);
        $this->load->view('upms/includes/footer');
    } //End of index()

    public function submit(){
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules('dept_info', 'Department', 'required|max_length[255]');
        $this->form_validation->set_rules('user_services[]', 'Service', 'required');
        $this->form_validation->set_rules('user_roles', 'Role', 'required');
        $this->form_validation->set_rules('user_fullname', 'Name', 'required|max_length[255]');
        //$this->form_validation->set_rules('user_location', 'Location', 'required');
        $this->form_validation->set_rules('mobile_number', 'Mobile', 'integer|exact_length[10]');
        $this->form_validation->set_rules('email_id', 'Email', 'valid_email');
        $this->form_validation->set_rules('login_username', 'Username', 'required|alpha_dash|max_length[20]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg','Error in inputs : '.validation_errors());
            $obj_id = strlen($objId)?$objId:null;
            $this->index($obj_id);
        } else {
            $user_services = array();
            $userServices = $this->input->post("user_services");
            if($userServices && count($userServices)) {
                foreach($userServices as $userService) {
                    $user_services[] = json_decode(html_entity_decode($userService));
                }//End of foreach()
            }//End of if
            
            $offices_info = array();
            $officesInfo = $this->input->post("offices_info");
            if($officesInfo && count($officesInfo)) {
                foreach($officesInfo as $officeInfo) {
                    $offices_info[] = json_decode(html_entity_decode($officeInfo));
                }//End of foreach()
            }//End of if
            
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
            $service_code = $user_services->service_code??'';
            $filter = array("login_username"=>$login_username);
            if(strlen($objId) == 0) {
                $dbRow = $this->users_model->get_row($filter);
            } else {
                $dbRow = false;
            }//End of if else
            
            if((strlen($objId)==0) && $dbRow) {
                $this->session->set_flashdata('flashMsg','Username already exists in the selected service');
                $this->index();
            } else {
                $rights = array();
                $user_rights = $this->input->post("user_rights");
                if($user_rights && count($user_rights)) {
                    foreach($user_rights as $right) {
                        $rights[] = json_decode($right);
                    }//End of foreach()
                }//End of if

                $forward_levels = array();
                $forwardLevels = $this->input->post("forward_levels");
                if($forwardLevels && count($forwardLevels)) {
                    foreach($forwardLevels as $levels) {
                        $forward_levels[] = json_decode($levels);
                    }//End of foreach()
                }//End of if

                $backward_levels = array();            
                $backwardLevels = $this->input->post("backward_levels");    
                if($backwardLevels && count($backwardLevels)) {
                    foreach($backwardLevels as $levels) {
                        $backward_levels[] = json_decode($levels);
                    }//End of foreach()
                }//End of if

                $generate_certificate_levels = array();         
                $generateGertificateLevels = $this->input->post("generate_certificate_levels");   
                if($generateGertificateLevels && count($generateGertificateLevels)) {
                    foreach($generateGertificateLevels as $levels) {
                        $generate_certificate_levels[] = json_decode($levels);
                    }//End of foreach()
                }//End of if
                
                $data = array(
                    "dept_info" => json_decode(html_entity_decode($this->input->post("dept_info"))),
                    "user_services" => $user_services,
                    "offices_info" => $offices_info,
                    "user_roles" => $user_roles,
                    "user_levels" => $user_levels,
                    "user_location" => json_decode(html_entity_decode($this->input->post("user_location"))),
                    "district_info" => json_decode(html_entity_decode($this->input->post("district_info"))),
                    "zone_info" => json_decode(html_entity_decode($this->input->post("zone_info"))),
                    "zone_circle" => $this->input->post("zone_circle"),
                    "office_info" => json_decode(html_entity_decode($this->input->post("office_info"))),
                    "user_fullname" => $this->input->post("user_fullname"),
                    "mobile_number" => $this->input->post("mobile_number"),
                    "email_id" => $this->input->post("email_id"),
                    "user_rights" => $rights,
                    "forward_levels" => $forward_levels,
                    "backward_levels" => $backward_levels,
                    "generate_certificate_levels" => $generate_certificate_levels,
                    "user_types" => array("utype_id" => 3, "utype_name" => "Staff/Dept user"), // 1 for developer/superadmin, 2 for stateadmin, 3 for staffs/dept users
                    "registered_by" => $this->session->loggedin_login_username,
                    "registration_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "status" => 1
                );     
                echo '<pre>'; var_dump($data); '</pre>'; die;
                if(strlen($objId)) {
                    $this->users_model->update_where(['_id' => new ObjectId($objId)], $data);
                    $this->session->set_flashdata('flashMsg','User has been successfully updated');
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
                    $this->session->set_flashdata('flashMsg','User has been successfully created');                
                }//End of if else
                redirect('spservices/upms/users');
            }//End of if else
        }//End of if else
    }//End of submit()

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

    function get_offices() {
        $this->load->model('upms/offices_model');
        $service_code = $this->input->post("service_code");
        $officeRows = $this->offices_model->get_rows(array("services_mapped.service_code" => $service_code));
        if($officeRows) { ?>
            <select name="offices_info[]" id="offices_info" class="form-control" multiple='multiple'>
                <option value="">Select office(s) </option>
                <?php if($officeRows) { 
                    foreach($officeRows as $office) {
                        $serviceObj = json_encode(array("office_code"=>$office->office_code, "office_name" => $office->office_name));
                        echo "<option value='{$serviceObj}'>{$office->office_name}</option>"; 
                    }//End of foreach()
                }//End of if ?>
            </select><script>$('#offices_info').multiselect();</script><?php
        } else {
            echo '';
        }//ENd of if else
    }//End of get_offices()

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
