<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends Upms {

    public function __construct() {
        parent::__construct();
        $this->isdev();
        $this->load->model('upms/roles_model');
    }//End of __construct()
  
    public function index($objId=null) {
        $data['dbrow'] = $this->roles_model->get_by_doc_id($objId);
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Roles"]);
        $this->load->view('upms/roles_view', $data);
        $this->load->view('upms/includes/footer');
    }//End of index()
    
    public function submit(){
        $objId = $this->input->post("obj_id");
        $obj_id = strlen($objId)?$objId:null;
        $role_code = strtoupper($this->input->post("role_code"));
        $this->form_validation->set_rules('role_name', 'Name', 'required|max_length[255]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        
        $filter = array('role_name' => $this->input->post("role_name"));
        $isExist = $this->roles_model->get_row($filter);
            
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg','Error in inputs : '.validation_errors());
            $this->index($obj_id);
        } elseif(!strlen($objId) && $isExist) {
            $this->session->set_flashdata('flashMsg','Role name already exist');
            $this->index($obj_id);
        } else {
            $data = array(
                "role_name" => $this->input->post("role_name"),
                "role_description" => $this->input->post("role_description"),
                "status" => 1
            );
            if(strlen($objId)) {
                $this->roles_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('flashMsg','Data has been successfully updated');
            } else {
                $data["role_code"] = $role_code;
                $this->roles_model->insert($data);
                $this->session->set_flashdata('flashMsg','Data has been successfully submitted');                
            }//End of if else
            redirect('spservices/upms/roles');
        }//End of if else
    }//End of submit()
                       
    function get_roles() {
        $fld_name=$this->input->post("fld_name");
        $fld_value=$this->input->post("fld_value");
        $roles = $this->roles_model->get_rows(array($fld_name => $fld_value)); ?>                   
        <select name="role_id" id="role_id" class="form-control">
            <option value="">Select a role </option>
            <?php if($roles) { 
                foreach($roles as $role) {
                    $role_id = $role->{'_id'}->{'$id'};
                    echo '<option value="'.$role_id.'">'.$role->rolename.'</option>';                   
                }//End of foreach()
            }//End of if ?>
        </select><?php
    }//End of get_roles()
}//End of Roles