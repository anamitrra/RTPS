<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Importjson extends Upms {
    
    public function __construct() {
        parent::__construct();
        $this->isdev();
        $this->load->model('upms/services_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/users_model');
    }//End of __construct()
  
    public function index() {
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Import data"]);
        $this->load->view('upms/importjson_view');
        $this->load->view('upms/includes/footer');            
    }//End of index()
      
    public function submit() {
        $this->form_validation->set_rules('collection_name', 'Collection', 'required|max_length[255]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg','Error in inputs : '.validation_errors());
            $this->index();
        } else {            
            $collectionName = $this->input->post("collection_name");
            if ($_FILES['uploadedfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['uploadedfile']['tmp_name'])) {
                $json = file_get_contents($_FILES['uploadedfile']['tmp_name']);
                $arr  = json_decode($json, true);
                if($collectionName === 'upms_services') {
                    $this->import_services($arr);
                } elseif($collectionName === 'upms_levels') {
                    $this->import_levels($arr);
                } elseif($collectionName === 'upms_users') {
                    $this->import_users($arr);
                }//End of elseif                
            } else {
                $this->session->set_flashdata('flashMsg','Error in file uploading');
                $this->index();
            }//End of if else
        }//End of if else
    }//End of submit()
    
    private function import_services($arr) {
        if(count($arr)) {            
            $service_code = $arr["service_code"]??'';
            $service_name = $arr["service_name"]??'';
            $dbRow = $this->services_model->get_row(["service_code"=>$service_code]);

            if((strlen($service_code)==0) || (strlen($service_name)==0)) {
                echo "Service code or name cannot be empty!<br>";
            } elseif($dbRow) {
                echo "Service data already exists against service code {$service_code}<br>";
            } else {
                $this->services_model->insert($arr);
            }//End of if else
            echo "Service data has been successfully added";
        } else {
            echo "No records found for import";
        }//End of if else
    }//End of import_services()
    
    private function import_levels($arr) {
        if(count($arr)) {
            $counter = 0;
            foreach($arr as $user) {
                $level_code = $user["level_code"]??'';
                $level_name = $user["level_name"]??'';
                $level_roles = $user["level_roles"]??array();
                $dbRow = $this->levels_model->get_row(["level_code"=>$level_code]);
                
                if((strlen($level_code)==0) || (strlen($level_name)==0) || (count($level_roles)==0)) {
                    echo "Task level code or name cannot be empty!<br>";
                } elseif($dbRow) {
                    echo "Task level data already exists against level code {$level_code}<br>";
                } else {
                    $this->levels_model->insert($user);
                    $counter++;
                }//End of if else
            }//End of foreach()
            echo "Total number of records inserted {$counter}";
        } else {
            echo "No records found for import";
        }//End of if else
    }//End of import_levels()
    
    private function import_users($arr) {
        if(count($arr)) {
            $this->load->model('upms/users_model');
            $counter = 0;
            foreach($arr as $user) {
                $login_username = $user["login_username"]??'';
                $user_fullname = $user["user_fullname"]??'';
                $user_levels = $user["user_levels"]??array();
                $user_services = $user["user_services"]??array();
                $dbRow = $this->users_model->get_row(["login_username"=>$login_username]);
                
                if((strlen($login_username)==0) || (strlen($user_fullname)==0)) {
                    echo "Username cannot be empty!<br>";
                } elseif((count($user_levels)==0) || (count($user_services)==0)) {
                    echo "Services or task levels cannot be empty!<br>";
                } elseif($dbRow) {
                    echo "User data already exists against username {$login_username}<br>";
                } else {
                    $this->users_model->insert($user);
                    $counter++;
                }//End of if else
            }//End of foreach()
            echo "Total number of records inserted {$counter}";
        } else {
            echo "No records found for import";
        }//End of if else
    }//End of import_users()
}//End of Importjson