<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Export extends Upms {

    public function __construct() {
        parent::__construct();
        $this->isdev();
        $this->load->model('upms/services_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/users_model');
    }//End of __construct()
    
    public function index() {
        $data["services"]= $this->services_model->get_rows(array("status"=>1));
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Export data"]);
        $this->load->view('upms/export_view', $data);
        $this->load->view('upms/includes/footer');
    }//End of index()
  
    public function process() {
        $service_code = $this->input->post("service_code");
        $serviceRow = $this->services_model->get_row(array("service_code" => $service_code));
        if($serviceRow) {
            $serviceRowArr = (array)$serviceRow;
            unset($serviceRowArr["_id"]);
            $serviceRowObj = json_encode($serviceRowArr); //$this->output->set_content_type('application/json')->set_output($serviceRowObj);            
            $fileLoc = FCPATH.'/storage/upms/';
            if(!is_dir($fileLoc)) {            
                mkdir($fileLoc, 0777, true);
                file_put_contents($fileLoc.'upms_services.json', $serviceRowObj);
            } else {
                file_put_contents($fileLoc.'upms_services.json', $serviceRowObj);
            }//End of if else
            $downloadLink = base_url('/storage/upms/');
            echo "<a href='{$downloadLink}upms_services.json' class='btn btn-success mb-1' target='_blank'>1. Click here to download upms_services collection</a><br>";
            
            //For upms_levels
            $levelRows = $this->levels_model->get_rows(array("level_services.service_code" => $service_code));
            if($levelRows) {
                $levelRowsArr = array();
                foreach($levelRows as $levelRow) {
                    $levelRowArr = (array)$levelRow;
                    unset($levelRowArr["_id"]);
                    $levelRowsArr[] = $levelRowArr;
                }//End of foreach()
                $levelRowsObj = json_encode($levelRowsArr);
                file_put_contents($fileLoc.'upms_levels.json', $levelRowsObj);
                echo "<a href='{$downloadLink}upms_levels.json' class='btn btn-primary mb-1' target='_blank'>2. Click here to download upms_levels collection</a><br>";
            }//End of if
                       
            //For upms_users
            $userRows = $this->users_model->get_rows(array("user_services.service_code" => $service_code));
            if($userRows) {
                $userRowsArr = array();
                foreach($userRows as $userRow) {
                    $userRowArr = (array)$userRow;
                    unset($userRowArr["_id"]);
                    $userRowsArr[] = $userRowArr;
                }//End of foreach()
                $userRowsObj = json_encode($userRowsArr);
                file_put_contents($fileLoc.'upms_users.json', $userRowsObj);
                echo "<a href='{$downloadLink}upms_users.json' class='btn btn-info mb-1' target='_blank'>3. Click here to download upms_users collection </a>";
            }//End of if
        } else {
            die("No records found");
        }//End of if else            
    }//End of process()
}//End of Export