<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Importcsv extends Upms {

    private $filePath = "D:\DATAS\UPMS\user_list_all.csv";
    
    public function __construct() {
        parent::__construct();
        $this->isdev();
        $this->load->model('upms/depts_model');
        $this->load->model('upms/services_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/users_model');
    }//End of __construct()
     
    public function index() {
        $data["depts"] = $this->depts_model->get_rows(array("status"=>1));
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Import data"]);
        $this->load->view('upms/importcsv_view', $data);
        $this->load->view('upms/includes/footer');            
    }//End of index()
      
    public function submit() {
        $this->form_validation->set_rules('dept_info', 'Department', 'required');
        $this->form_validation->set_rules('service_info', 'Service', 'required');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg','Error in inputs : '.validation_errors());
            $this->index();
        } else {
            $dept_info = json_decode(html_entity_decode($this->input->post("dept_info")));
            $service_info = json_decode(html_entity_decode($this->input->post("service_info")));
            //echo $dept_info->dept_code.' : '.$service_info->service_code;pre((array)$dept_info);
            if ($_FILES['uploadedfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['uploadedfile']['tmp_name'])) {
                $this->filePath = $_FILES['uploadedfile']['tmp_name'];
                $this->process($dept_info, $service_info, $this->filePath);
            } else {
                $this->session->set_flashdata('flashMsg','Error in file uploading');
                $this->index();
            }//End of if else
        }//End of if else
    }//End of submit()
      
    public function process($dept_info, $service_info, $filePath=null) {
        $this->load->model('upms/offices_model');
        if (file_exists($this->filePath) || file_exists($filePath)) {   
            $file_to_read = fopen($this->filePath, 'r');
            $lineNo = 0;
            $counter = 0;
            while (($line = fgetcsv($file_to_read)) !== FALSE) {
                if($lineNo){ //Skip the heading line
                    $login_username = trim($line[0]);
                    $dbRow = $this->users_model->get_row(["login_username"=>$login_username]);
                    if($dbRow) {
                        echo "User already exist against username : {$login_username} <br>";
                    } else {
                        $designation = trim($line[5]);
                        if($designation === 'Employment Exchange Operator') {
                            $role_code = "EEO";
                            $role_name = "Employment Exchange Operator";
                            $level_no = 1;
                            $level_name = "Employment Exchange Operator";
                        } elseif($designation === 'Dealing Assistant') {
                            $role_code = "DA";
                            $role_name = "Dealing Assistant";
                            $level_no = 2;
                            $level_name = "Dealing Assistant";                            
                        } elseif($designation === 'Designated Public Servant (DPS)') {
                            $role_code = "DPS";
                            $role_name = "Designated Public Servant";
                            $level_no = 3;
                            $level_name = "Designated Public Servant";                             
                        } else {
                            $role_code = "";
                            $role_name = "";
                            $level_no = 0;
                            $level_name = "";                             
                        }//End of if else

                        if($level_no && strlen($role_code)) {
                            $office_name = trim($line[3]);                                                        
                            $officeRow = $this->offices_model->get_row(['office_name' => $office_name]);
                            if($officeRow){
                                $offices_info = array("office_code"=>$officeRow->office_code, "office_name"=>$officeRow->office_name);
                            } else {
                                $office_code = strtoupper(uniqid($counter));
                                $offices_data = array(
                                    "office_code" => $office_code, 
                                    "office_name" => $office_name,
                                    "services_mapped" => array((array)$service_info),
                                    "office_address" => "",
                                    "office_description" => "",
                                    "status"=>1
                                );
                                $this->offices_model->insert($offices_data);
                                $offices_info = array("office_code" => $office_code, "office_name" => $office_name);
                            }//End of if else
                            $data = array(
                                "login_username" => $login_username,
                                "dept_info" => (array)$dept_info,
                                "user_services" => array((array)$service_info),
                                "user_roles" => array("role_code"=>$role_code, "role_name"=>$role_name, "level_no"=>$level_no, "level_name"=>$level_name),
                                "user_levels" => array("level_no"=>$level_no, "level_name"=>$level_name),
                                "offices_info" => array($offices_info),
                                "user_fullname" => $line[2],
                                "mobile_number" => $line[1],
                                "email_id" => '',
                                "user_types" => array("utype_id" => 3, "utype_name" => "Staff/Dept user"),
                                "registered_by" => "EXPORTED_FROM_SP",
                                "registration_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                                "status" => 1,
                                "exported_from" => "CUSTOM_CSV_FILE"
                            ); //echo json_encode($data, JSON_PRETTY_PRINT); pre($data);
                            $this->users_model->insert($data);
                            $counter++;
                            echo "User data against username : {$login_username} has been inserted successfully!<br>";
                        }//End of if
                    }//End of if else
                }//End of if else
                $lineNo++;
            }//End of while
            fclose($file_to_read);
            echo "Total no. of users inserted : {$counter}";
        } else {
            echo "File does not exist at {$this->filePath}";
        }//End of if else            
    }//End of process()
    
    public function delete_by_dept_and_service($dept_code=null, $service_code=null) { //die($dept_code.$service_code);
        if(strlen($dept_code) && strlen($service_code)) {
            $filter = array(
                "dept_info.dept_code" => $dept_code,
                "user_services.service_code" => $service_code,
                "exported_from"=> "CUSTOM_CSV_FILE"
            );
            $res = $this->users_model->delete_by_filter($filter);
            $this->session->set_flashdata('flashMsg','Data has been successfully deleted');
        } else {
            $this->session->set_flashdata('flashMsg','Department or Service cannot be empty');
        }//End of if else
        redirect('spservices/upms/users');
    }//End of delete_by_dept_and_service()
  
    public function get_json() {        
        if (file_exists($this->filePath)) {   
            $file_to_read = fopen($this->filePath, 'r');
            $data = array();
            $lineNo=0;
            while (($line = fgetcsv($file_to_read)) !== FALSE) {
                if($lineNo == 0){
                    for($i = 0; $i < count($line); $i++){
                        ${"field".$i} = $line[$i];
                    }//End of for()
                } else {
                    for($i = 0; $i < count($line); $i++){
                        $doc[${"field".$i}] = $line[$i];
                    }//End of for()
                    $data[] = $doc;
                }//End of if else
                $lineNo++;
            }//End of while
            header('Content-type: application/json');
            echo json_encode($data, JSON_PRETTY_PRINT);
            fclose($file_to_read);
        } else {
            echo "File does not exist at ".$this->filePath;
        }//End of if else            
    }//End of get_json()
    
    public function get_table() {
        $file_to_read = fopen($this->filePath, 'r');
        if ($file_to_read !== FALSE) {
            echo "<table>\n";
            $lineNo=1;
            while (($data = fgetcsv($file_to_read, 100, ',')) !== FALSE) {
                echo "<tr>";
                for ($i = 0; $i < count($data); $i++) {
                    echo "<td>{$data[$i]}</td>";
                }
                echo "</tr>\n";
                $lineNo++;
            }
            echo "</table>\n";
            fclose($file_to_read);
        }//End of if
    }//End of get_table()
}//End of Importcsv