<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Offices extends admin {

    public function __construct() {
        parent::__construct();
        // $this->isdev();
        $this->load->model('admin/services_model');
        $this->load->model('admin/offices_model');
    }//End of __construct()
  
    public function index($objId=null) {
        $data['dbrow'] = $this->offices_model->get_by_doc_id($objId);
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Offices"]);
        $this->load->view('upms/offices_view', $data);
        $this->load->view('upms/includes/footer');
    }//End of index()
    
    public function submit(){
        $objId = $this->input->post("obj_id");
        $obj_id = strlen($objId)?$objId:null;
        $this->form_validation->set_rules('office_name', 'Name', 'required|max_length[255]');
        $this->form_validation->set_rules('services_mapped[]', 'Services', 'required|max_length[255]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg','Error in inputs : '.validation_errors());
            $this->index($obj_id);
        } else {
            $services_mapped = array();
            $servicesMapped = $this->input->post("services_mapped");
            if($servicesMapped && count($servicesMapped)) {
                foreach($servicesMapped as $serviceMapped) {
                    $services_mapped[] = json_decode(html_entity_decode($serviceMapped));
                }//End of foreach()
            }//End of if
            
            $data = array(
                "office_name" => $this->input->post("office_name"),
                "services_mapped" => $services_mapped,
                "office_address" => $this->input->post("office_address"),
                "office_description" => $this->input->post("office_description"),
                "status" => 1
            ); //pre($data);
            if(strlen($objId)) {
                $office_code = $this->input->post("office_code");
                $this->offices_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('flashMsg','Data has been successfully updated');
            } else {
                $office_code = $this->getID(7);
                $data["office_code"] = $office_code;
                $this->offices_model->insert($data);
                $this->session->set_flashdata('flashMsg','Data has been successfully submitted');                
            }//End of if else
            
            /*if($servicesMapped && count($servicesMapped)) {
                foreach($servicesMapped as $serviceMapped) {
                    $servicesMappedDecoded = json_decode(html_entity_decode($serviceMapped));
                    $serviceRow = $this->services_model->get_row(["service_code" => $servicesMappedDecoded->service_code]);
                    if($serviceRow) { echo $servicesMappedDecoded->service_code." => ";
                        $officesExisting = $serviceRow->offices??array();
                        if(count($officesExisting)) {
                            foreach($officesExisting as $office) {
                                if($office->office_code !== $office_code) { echo $servicesMappedDecoded->service_code." : ".$office->office_code." !==".$office_code."<br>";
                                     $officesNew = array(
                                        "office_name" => $this->input->post("office_name"),
                                        "office_code" => $office_code
                                    );
                                    $offices = array_merge($officesExisting, $officesNew);
                                }//End of if                               
                            }//End of foreach()                            
                        } else {
                            $offices[] = array(
                                "office_name" => $this->input->post("office_name"),
                                "office_code" => $office_code
                            );
                        }//End of if else
                        pre($offices);
                        $serviceData = array("offices" => $offices);
                        $this->services_model->update_where(['service_code' =>$servicesMappedDecoded->service_code], $serviceData);
                    }//End of if
                }//End of foreach()
            }//End of if*/
            redirect('spservices/upms/offices');
        }//End of if else
    }//End of submit()
                       
    function get_offices() {
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
    
    function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->offices_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        } //End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "UPMS-" . date('Y') . "-" . $number;
        return $str;
    }//End of generateID()
  
    public function deleteme($objId=null) {
        if($this->checkObjectId($objId)) {
            $this->offices_model->delete_by_filter(['_id' => new ObjectId($objId)]);
            $this->session->set_flashdata('flashMsg','Data has been successfully deleted');
        } else {
            $this->session->set_flashdata('flashMsg','Invalid object id');
        }//End of if else
        redirect('spservices/upms/offices');
    }//End of deleteme()
    
    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Offices