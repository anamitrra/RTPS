<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Svcs extends Upms {

    public function __construct() {
        parent::__construct();
        $this->isdev();
        $this->load->model('upms/depts_model');
        $this->load->model('upms/services_model');
        $this->load->model('upms/dropdown_model');
        $this->load->model('upms/rights_model');
    }//End of __construct()
  
    public function index($objId=null) {
        $data['dbrow'] = $this->services_model->get_by_doc_id($objId);
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Services"]);
        $this->load->view('upms/services_view', $data);
        $this->load->view('upms/includes/footer');
    }//End of index()
    
    public function submit(){        
        $objId = $this->input->post("obj_id");
        $obj_id = strlen($objId)?$objId:null;
        $service_code = strtoupper($this->input->post("service_code"));
        $isExist = $this->services_model->get_row(array("service_code" => $service_code));
        $this->form_validation->set_rules('dept_info', 'Department', 'required|max_length[255]');
        $this->form_validation->set_rules('service_name', 'Service Name', 'required|max_length[255]');
        $this->form_validation->set_rules('service_code', 'Service Code', 'required|max_length[255]');
        $this->form_validation->set_rules('preview_link', 'Preview link', 'required|max_length[255]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg','Error in inputs : '.validation_errors());            
            $this->index($obj_id);
        } elseif(!strlen($objId) && $isExist) {
            $this->session->set_flashdata('flashMsg','Service code already exist');
            $this->index($obj_id);
        } else {            
            $field_names = $this->input->post("field_names");
            $field_levels = $this->input->post("field_levels");
            $field_types = $this->input->post("field_types");
            $level_nos = $this->input->post("level_nos");
            $right_codes = $this->input->post("right_codes");
            
            $customFields = array();
            if(count($field_names)) {                
                foreach($field_names as $field_key=>$field_name) {
                    $customField = array(
                        "field_name" => $field_name,
                        "field_level" => $field_levels[$field_key],
                        "field_type" => $field_types[$field_key],
                        "level_no" => isset($level_nos[$field_key])?(int)$level_nos[$field_key]:0,
                        "right_code" => $right_codes[$field_key]
                    );
                    $ddRows = $this->dropdown_model->get_row(array("service_code" => $service_code, "field_name" => $field_name));
                    if($ddRows) {
                        $customField["dropdown_data"] = $ddRows->dropdown_data;
                    }
                    $customFields[] = $customField;
                }//End of foreach()
            }//End of if
            
            if(isset($_FILES["input_file"])) {
                if(is_uploaded_file($_FILES['input_file']['tmp_name'])) {
                    $this->load->helper("cifileupload");
                    $res = cifileupload("input_file");
                    $fileUploaded = ($res["upload_status"])?$res["uploaded_path"]:null;                
                } else {
                    $fileUploaded = null;
                }//End of if else
            } else {
               $fileUploaded = null;
            }//End of if else
                 
            $data = array(
                "dept_info" => json_decode(html_entity_decode($this->input->post("dept_info"))),
                "service_name" => $this->input->post("service_name"),
                "preview_link" => $this->input->post("preview_link"),
                "dsc_required" => $this->input->post("dsc_required"),
                "service_mode" => $this->input->post("service_mode"),
                "timeline" => $this->input->post("timeline"),
                "service_description" => $this->input->post("service_description"),
                "input_file" => $fileUploaded,
                "custom_fields" => $customFields,
                "status" => 1
            );
            //echo '<pre>'; var_dump($data); die;   
            if(strlen($objId)) {
                $this->services_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('flashMsg','Data has been successfully updated');
            } else {
                $data["service_code"] = $service_code;
                $this->services_model->insert($data);
                $this->session->set_flashdata('flashMsg','Data has been successfully submitted');                
            }//End of if else
            redirect('spservices/upms/svcs');
        }//End of if else
    }//End of submit()
    
    public function create_table(){
        $custom_fields = $this->input->post("custom_fields");
        $rights = $this->rights_model->get_rows(array("status"=>1)); ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Field name</th>
                    <th>Field level</th>
                    <th>Field type</th>
                    <!--<th data-toggle="tooltip" title="At which level users can access this field">Level no.</th>-->
                    <th>
                        <div data-toggle="tooltip" data-placement="top" title="When will it appear">Right</div>
                    </th>
                    <th style="text-align: center">Options</th>
                </tr>
            </thead>
            <tbody>
                <?php for($i=0; $i<$custom_fields; $i++) { ?>
                <tr>
                    <td><input id="field_name-<?=$i?>" name="field_names[]" class="form-control" required type="text" /></td>
                    <td><input id="field_level-<?=$i?>" name="field_levels[]" class="form-control" required type="text" /></td>
                    <td>
                        <select id="field_type-<?=$i?>" name="field_types[]" class="form-control field_types" required>
                            <option value="">Choose</option>
                            <option value="text">Text</option>
                            <option value="textarea">Text-area</option>
                            <option value="radio">Radio button</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="dropdown">Drop-down</option>
                        </select>
                    </td>
                    <!--<td>
                        <select id="level_no-<?=$i?>" name="level_nos[]" class="form-control">
                            <option value="0">Please Select</option>
                            <?php for($lno=1; $lno <= 10; $lno++) { ?>
                                <option value="<?=$lno?>"><?=sprintf('%02d', $lno)?></option>
                            <?php }//End of for() ?>
                        </select>
                    </td>-->
                    <td>
                        <select id="right_code-<?=$i?>" name="right_codes[]" class="form-control">
                            <?php if ($rights) {
                                echo '<option value="0">Please Select</option>';
                                foreach ($rights as $key => $right) {
                                    echo "<option value='{$right->right_code}'>{$right->right_name}</option>";
                                }//End of foreach()
                            } else {
                                echo '<option value="0">No records found</option>';
                            }//End of if else ?>
                        </select>
                    </td>
                    <td style="text-align: center">
                        <button class="btn btn-danger deletetblrow"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php }//End of create_table()
    
     public function create_dropdown(){
        $service_code = $this->input->post("service_code");
        $field_name = $this->input->post("field_name");
        $dropdown_data = json_decode(html_entity_decode($this->input->post("dropdown_data")));
        $data = array(
            "service_code" => $service_code,
            "field_name" => $field_name,
            "dropdown_data" => $dropdown_data             
        );
        $dbFilter = array("service_code" => $service_code, "field_name" => $field_name);
        $isExist = $this->dropdown_model->get_row($dbFilter);
        if($isExist) {
            $this->dropdown_model->update_where($dbFilter, $data);
            $resArr = array('post_status' => 1, 'post_msg' => 'Drop-down has been successfully updated');
        } else {
            $this->dropdown_model->insert($data);
            $resArr = array('post_status' => 1, 'post_msg' => 'Drop-down has been successfully created');
        }//End of if else
        $json_obj = json_encode($resArr);
        $this->output->set_content_type('application/json')->set_output($json_obj);
     }//End of create_dropdown()
    
     public function get_dropdown(){
        $service_code = $this->input->post("service_code");
        $field_name = $this->input->post("field_name");
        $ddRows = $this->dropdown_model->get_row(array("service_code" => $service_code, "field_name" => $field_name)); ?>
        <table class="table table-bordered" id="dropdownTbl">
            <thead>
                <tr>
                    <th>Dropdown value</th>
                    <th>Dropdown text</th>
                    <th style="width:65px;text-align: center">#</th>
                </tr>
            </thead>
            <tbody>
                <?php if($ddRows) { 
                    foreach ($ddRows->dropdown_data as $idx=>$rows) {
                        if ($idx == 0) {
                            $btn = '<button class="btn btn-info" id="addlatblrow" type="button"><i class="fa fa-plus-circle"></i></button>';
                        } else {
                            $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash"></i></button>';
                        }// End of if else ?>
                        <tr>
                            <td><input name="dropdown_values[]" value="<?=$rows->dropdown_value?>" class="form-control dd_values" type="text" /></td>
                            <td><input name="dropdown_texts[]" value="<?=$rows->dropdown_text?>" class="form-control dd_texts" type="text" /></td>
                            <td><?= $btn ?></td>
                        </tr><?php
                    }//End of foreach()
                } else { ?>
                    <tr>
                        <td><input name="dropdown_values[]" class="form-control dd_values" type="text" /></td>
                        <td><input name="dropdown_texts[]" class="form-control dd_texts" type="text" /></td>
                        <td style="text-align:center">
                            <button class="btn btn-info" id="addlatblrow" type="button">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                        </td>
                    </tr>
                <?php }//End of if else ?>
            </tbody>
        </table>
     <?php }//End of get_dropdown()
    
     public function check_file(){
        $file_path = $this->input->post("file_path");
        $rawFilePath = FCPATH.'application/modules/spservices/views/'.$file_path;
        $absFilePath = str_replace('\\', '/', $rawFilePath);
        if(file_exists(FCPATH.'application/modules/spservices/views/'.$file_path)) {
            $resArr = array('post_status' => 1, 'post_msg' => 'File successfully found at '.$absFilePath);
        } else {
            $resArr = array('post_status' => 0, 'post_msg' => 'File does not exist at '.$absFilePath);
        }//End of if else
        $json_obj = json_encode($resArr);
        $this->output->set_content_type('application/json')->set_output($json_obj);
    }//End of check_file()
                                
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
  
    public function deleteme($objId=null) {
        if($this->checkObjectId($objId)) {
            $this->services_model->delete_by_filter(['_id' => new ObjectId($objId)]);
            $this->session->set_flashdata('flashMsg','Data has been successfully deleted');
        } else {
            $this->session->set_flashdata('flashMsg','Invalid object id');
        }//End of if else
        redirect('spservices/upms/services');
    }//End of deleteme()
    
    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Svcs