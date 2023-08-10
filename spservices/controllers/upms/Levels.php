<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Levels extends Upms {

    public function __construct() {
        parent::__construct();
        $this->isdev();
        $this->load->model('upms/depts_model');
        $this->load->model('upms/services_model');
        $this->load->model('upms/roles_model');
        $this->load->model('upms/rights_model');
        $this->load->model('upms/levels_model');
    }//End of __construct()
  
    public function index($objId=null) {
        $data['dbrow'] = $this->levels_model->get_by_doc_id($objId);
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Task levels"]);
        $this->load->view('upms/levels_view', $data);
        $this->load->view('upms/includes/footer');
    }//End of index()
    
    public function submit(){
        $objId = $this->checkObjectId($this->input->post("obj_id"))?$this->input->post("obj_id"):null;
        $this->form_validation->set_rules('dept_info', 'Department', 'required|max_length[255]');
        $this->form_validation->set_rules('level_services', 'Service', 'required|max_length[255]');
        $this->form_validation->set_rules('level_no', 'Task level no.', 'required|max_length[255]');
        $this->form_validation->set_rules('level_roles', 'Role', 'required|max_length[255]');
        $this->form_validation->set_rules('level_name', 'Task level name', 'required|max_length[255]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg','Error in inputs : '.validation_errors());
            $this->index($objId);
        } else {
            $levelServices = json_decode(html_entity_decode($this->input->post("level_services")));
            $levelNo = (int)$this->input->post("level_no");
            $levelRoles = json_decode(html_entity_decode($this->input->post("level_roles")));
            $filter = array(
                'level_services.service_code' => $levelServices->service_code,
                'level_no' => $levelNo,
                'level_roles.role_code' => $levelRoles->role_code
            );
            $levelRow = $this->levels_model->get_row($filter);
            
            $level_rights = $this->input->post("level_rights");
            $rights = array();
            if($level_rights && count($level_rights)) {
                foreach($level_rights as $right) {
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
                "level_services" => $levelServices,
                "level_no" => $levelNo,
                "level_roles" => $levelRoles,
                "level_name" => $this->input->post("level_name"),
                "level_description" => $this->input->post("level_description"),
                "level_rights" => $rights,
                "backward_levels" => $backward_levels,
                "forward_levels" => $forward_levels,
                "generate_certificate_levels" => $generate_certificate_levels,
                "query_payment_amount" => $this->input->post("query_payment_amount"),
                "status" => 1
            );         
            if($levelRow) {
                $this->levels_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('flashMsg','Data has been successfully updated');
            } else {
                $this->levels_model->insert($data);
                $this->session->set_flashdata('flashMsg','Data has been successfully submitted');                
            }//End of if else
            //echo '<pre>'; var_dump($data); '</pre>'; die;
            redirect('spservices/upms/levels');
        }//End of if else
    }//End of submit()
                       
    function get_levels() {
        $service_code=$this->input->post("service_code");
        $levels = $this->levels_model->get_rows(array("level_services.service_code" => $service_code)); ?>                   
        <select name="user_levels" id="user_levels" class="form-control">
            <option value="">Select a processing level </option>
            <?php if($levels) { 
                foreach($levels as $level) {
                    $level_id = $level->{'_id'}->{'$id'};
                    $roleObj = json_encode(array("level_id"=>$level_id, "level_no"=>$level->level_no, "level_name" => $level->level_name));
                    echo "<option value='".$roleObj."'>".$level->level_name."</option>";                 
                }//End of foreach()
            }//End of if ?>
        </select><?php
    }//End of get_levels()
                           
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
                       
    function get_rights() {
        $service_code = $this->input->post("service_code");
        $level_no = (int)$this->input->post("level_no");     
        $rights = $this->rights_model->get_rows(array("status"=>1)); ?>                   
        <table class="table table-bordered">
            <thead>
                <tr style="text-transform: uppercase">
                    <th colspan="2">Tasks/Rights allocated for the selected level</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rights as $urights) {
                    $right_code = $urights->right_code??'';
                    $right_name = $urights->right_name;
                    $rightObj = json_encode(array("right_code"=>$right_code, "right_name" => $right_name));
                    $filterLevels = array();
                    if($right_code === 'FORWARD') {
                        $lbl_title = "Select level(s) where application can be forward";
                        $filterLevels = array(
                            "level_services.service_code" => $service_code,
                            "level_no" => array('$gte' => $level_no),
                            "status" =>1 
                        );
                    } elseif($right_code === 'BACKWARD') {
                        $lbl_title = "Select level(s) where application can be revert back";
                        $filterLevels = array(
                            "level_services.service_code" => $service_code,
                            "level_no" => array('$lte' => $level_no),
                            "status" =>1 
                        );
                    } elseif($right_code === 'GENERATE_CERTIFICATE') {
                        $lbl_title = "Select level(s) whose processes can allows to generate certificate";
                        $filterLevels = array(
                            "level_services.service_code" => $service_code,
                            "status" =>1 
                        );
                    } else {
                        $lbl_title = $urights->right_description;
                    }//End of if else
                    $levels = $this->levels_model->get_rows($filterLevels); ?>
                    <tr>
                        <td style="font-weight:bold; width: 200px">
                            <input name="level_rights[]" value='<?=$rightObj?>' id="<?=$right_code?>" class="level_rights" type="checkbox" />
                            <label for="<?=$right_code?>"><?=$right_name?></label>                                                
                        </td>
                        <td>
                            <span id="<?=$right_code?>-SPAN" style="display:none">
                                <?php if(in_array($right_code, ['FORWARD', 'BACKWARD', 'GENERATE_CERTIFICATE'])) { ?>
                                    <label for="<?=strtolower($right_code)?>_levels"><?=$lbl_title?></label><br>
                                    <select name="<?=strtolower($right_code)?>_levels[]" id="<?=strtolower($right_code)?>_levels" class="form-control" multiple>
                                        <option value="" disabled="">Select level(s) </option>
                                        <?php if($levels) { 
                                            foreach($levels as $level) {
                                                $levelsObj = json_encode(array("level_no" => $level->level_no, "level_name" => $level->level_name, "service_code" => $service_code));
                                                echo "<option value='".$levelsObj."'>".$level->level_name."</option>";                 
                                            }//End of foreach()
                                        }//End of if ?>
                                    </select>
                                    <script>$('#<?=strtolower($right_code)?>_levels').multiSelect();</script>
                                <?php } elseif($right_code === 'QUERY_PAYMENT') {
                                    echo "<input name='query_payment_amount' placeholder='Enter a default payment amount' class='form-control' type='number' />";
                                }//End of if else ?>
                            </span>
                        </td>
                    </tr>
                <?php }//End of foreach() ?>
            </tbody>
        </table> <?php
    }//End of get_rights()
  
    public function deleteme($objId=null) {
        if($this->checkObjectId($objId)) {
            $this->levels_model->delete_by_filter(['_id' => new ObjectId($objId)]);
            $this->session->set_flashdata('flashMsg','Data has been successfully deleted');
        } else {
            $this->session->set_flashdata('flashMsg','Invalid object id');
        }//End of if else
        redirect('spservices/upms/levels');
    }//End of deleteme()
    
    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Levels