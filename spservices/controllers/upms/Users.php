<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Upms {

    public function __construct() {
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
    }//End of __construct()
  
    public function index($objId=null) {
        $data['dbrow'] = $this->users_model->get_by_doc_id($objId);
        $this->load->model('employment_nonaadhaar/district_model');//For Employment Exchange only      
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Users"]);
        $this->load->view('upms/users_view', $data);
        $this->load->view('upms/includes/footer');
    }//End of index()
    
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
                
                $additional_roles = array();
                $additionalRoles = $this->input->post("additional_roles");
                if($additionalRoles && count($additionalRoles)) {
                    foreach($additionalRoles as $additionalRole) {
                        $additional_roles[] = json_decode(html_entity_decode($additionalRole));
                    }//End of foreach()
                }//End of if
                
                $data = array(
                    "dept_info" => json_decode(html_entity_decode($this->input->post("dept_info"))),
                    "user_services" => $user_services,
                    "offices_info" => $offices_info,
                    "user_roles" => $user_roles,
                    "additional_roles" => $additional_roles,
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
                    "reports_url" => $this->input->post("reports_url"),
                    "status" => 1
                );     
                //echo '<pre>'; var_dump($data); '</pre>'; die;
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
           
    function get_empoffices() {
        $district_id = (int)$this->input->post("district_id");
        $this->load->model('employment_nonaadhaar/employment_office_model');
        $offices = $this->employment_office_model->get_rows(array("district_id"=>$district_id)); ?>                   
        <select name="office_info" id="office_info" class="form-control">
            <option value="">Select an office </option>
            <?php if($offices) {                
                foreach($offices as $office) {
                    $officeObj = json_encode(array("office_id"=>$office->employment_id, "office_name" => $office->employment_exchange_office));
                    echo "<option value='".$officeObj."'>".$office->employment_exchange_office."</option>";
                }//End of foreach()
            } else {
                echo "<option value=''>No records found</option>";
            }//End of if else ?>
        </select><?php
    }//End of get_empoffices()
                       
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
                           
    function get_rights() {
        $service_code = $this->input->post("service_code");
        $level_no = (int)$this->input->post("level_no");
        $role_code = $this->input->post("role_code");
        $filter = array(
            'level_services.service_code' => $service_code,
            //'level_no' => $level_no,
            'level_roles.role_code' => $role_code
        );
        $levels = $this->levels_model->get_row($filter); ?>       
        <div class="accordion" id="accordionTasks">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTasks">
                    <button class="btn btn-primary w-100" type="button" data-toggle="collapse" data-target="#rights_table" aria-expanded="true" aria-controls="rights_table" style="font-size:18px; text-transform: uppercase; font-weight: bold">
                        <span style="float:left">User Rights/Tasks allocations (Only for viewing purpose)</span>
                        <span style="float:right"><i class="fa fa-chevron-circle-down"></i></span>
                    </button>
                </h2><!--End of .accordion-header -->
                <div id="rights_table" class="accordion-collapse collapse" aria-labelledby="headingTasks" data-parent="#accordionTasks">
                    <div class="accordion-body p-1">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr style="text-transform: uppercase">
                                    <th colspan="2">Tasks/Rights allocated for the selected level</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if($levels) {
                                $level_no = $levels->level_no;
                                $level_rights = $levels->level_rights;
                                $backward_levels = $levels->backward_levels;
                                $forward_levels = $levels->forward_levels;
                                $generate_certificate_levels = $levels->generate_certificate_levels;

                                if(is_array($level_rights) && count($level_rights)) {                                    
                                    foreach ($level_rights as $uright) {
                                        $right_code = $uright->right_code;
                                        if($right_code === 'FORWARD') {
                                            $levelsArray = $forward_levels;
                                            $lbl_title = "Select level(s) where application can be forward";
                                        } elseif($right_code === 'BACKWARD') {
                                            $levelsArray = $backward_levels;
                                            $lbl_title = "Select level(s) where application can be revert back";
                                        } elseif($right_code === 'GENERATE_CERTIFICATE') {
                                            $levelsArray = $generate_certificate_levels;
                                            $lbl_title = "Select level(s) whose processes can allows to generate certificate";
                                        } else {
                                            $levelsArray = array();
                                            $lbl_title = "";
                                        }//End of if else ?>
                                        <tr>
                                            <td style="font-weight:bold">
                                                <input name="user_rights[]" value='<?=json_encode($uright)?>' id="user_right<?=$right_code?>" checked type="checkbox" />
                                                <label for="user_right<?=$right_code?>"><?=$uright->right_name?></label>                                                
                                            </td>
                                            <td>
                                                <?php if(($right_code === 'FORWARD') || ($right_code === 'BACKWARD') || ($right_code === 'GENERATE_CERTIFICATE')) { ?>
                                                    <label for="<?=strtolower($right_code)?>_levels"><?=$lbl_title?></label><br>
                                                    <select name="<?=strtolower($right_code)?>_levels[]" id="<?=strtolower($right_code)?>_levels" class="form-control" multiple>
                                                        <option value="" disabled="">Select level(s) </option>
                                                        <?php if($levelsArray) { 
                                                            foreach($levelsArray as $level) {
                                                                $levelsObj = json_encode(array("level_no" => $level->level_no, "level_name" => $level->level_name, "service_code" => $service_code));
                                                                echo "<option value='".$levelsObj."' selected>".$level->level_name."</option>";                 
                                                            }//End of foreach()
                                                        }//End of if ?>
                                                    </select>
                                                    <script>$('#<?=strtolower($right_code)?>_levels').multiselect();</script>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                    <?php }//End of foreach()
                                } else {
                                    echo '<tr><td colspan="2">No rights allocated for the provided level</td></tr>';
                                }//End of if else
                            } else {
                                echo '<tr><td colspan="2">No records found for the provided level</td></tr>';
                            }//End of if ?>
                            </tbody>
                        </table>
                    </div><!--End of .accordion-body -->
                </div><!--End of .accordion-collapse -->
            </div><!--End of .accordion-item -->
        </div><!--End of .accordion -->    
        <?php
    }//End of get_rights()
      
    public function profile($objId=null) {
        if($this->checkObjectId($objId)) {
            $user = $this->users_model->get_row(array('_id' => new ObjectId($objId)));
        } else {
            $user = $this->users_model->get_row(array("login_username"=>$this->session->loggedin_login_username));
        }//End of if else        
        if($user) {
            $this->load->model('employment_nonaadhaar/district_model');//For Employment Exchange only
            $data['dbrow'] = $user;
            $this->load->view('upms/includes/header', ["header_title" => "UPMS : Profile"]);
            $this->load->view('upms/userprofile_view', $data);
            $this->load->view('upms/includes/footer');
        } else {
            $this->session->set_flashdata('flashMsg','User does not exist!');                
            redirect('spservices/upms/');
        }//End of if else
    }//End of profile()
  
    public function changepass($login_username=null) {
        $loginUsername = (strlen($login_username))?$login_username:$this->session->loggedin_login_username;
        $user = $this->users_model->get_row(array("login_username"=>$loginUsername));
        if($user) {
            $data['dbrow'] = $user;
            $this->load->view('upms/includes/header', ["header_title" => "UPMS : Change password"]);
            $this->load->view('upms/changepass_view', $data);
            $this->load->view('upms/includes/footer');
        } else {
            $this->session->set_flashdata('flashMsg','User does not exist!');                
            redirect('spservices/upms/');
        }//End of if else
    }//End of changepass()
    
    public function updatepass() {
        $this->load->library("form_validation");
        $this->form_validation->set_rules("login_username", "Username", "required");
        $this->form_validation->set_rules("pass_new", "New Pasword", "required|min_length[4]");
        $this->form_validation->set_rules("pass_conf", "Confirmation Password", "required|matches[pass_new]");
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        $login_username = $this->input->post("login_username");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg','Error in inputs : '.validation_errors());
            $this->changepass($login_username);
        } else {            
            $login_password = $this->input->post("pass_new");
            $salt = uniqid("", true);
            $algo = "6";
            $rounds = "5050";
            $cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;
            $hashedPassword = crypt($login_password, $cryptSalt);                
            $this->users_model->update_where(['login_username' => $login_username], ['login_password'=>$hashedPassword]);     
            $this->session->set_flashdata('flashMsg','Password has been successfully updated');
            redirect('spservices/upms/users');
        }//End of if else
    }//End of updatepass()
  
    public function deleteme($objId=null) {
        if($this->checkObjectId($objId)) {
            $this->users_model->delete_by_filter(['_id' => new ObjectId($objId)]);
            $this->session->set_flashdata('flashMsg','Data has been successfully deleted');
        } else {
            $this->session->set_flashdata('flashMsg','Invalid object id');
        }//End of if else
        redirect('spservices/upms/users');
    }//End of deleteme()
    
    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Users