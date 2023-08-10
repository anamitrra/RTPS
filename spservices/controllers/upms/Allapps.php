<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Allapps extends Upms {

    public function __construct() {
        parent::__construct();
        //$this->isloggedin();
        $this->load->model('upms/services_model');
        $this->load->model('upms/roles_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/users_model');
        $this->load->model('upms/applications_model'); 
        $this->load->config('upms_config');
    }//End of __construct()
  
    public function index() {
        $this->load->view('upms/includes/header');
        $this->load->view('upms/allapps_view');
        $this->load->view('upms/includes/footer');
    }//End of index()
        
    public function get_records() {
        $columns = array(
            0 => "user_fullname",
            1 => "login_username",
            2 => "mobile_number",
            3 => "user_services"
        );
        
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        if ($this->input->post("order")) {
            $order = $columns[(int) $this->input->post("order")[0]["column"]];
            $dir = $this->input->post("order")[0]["dir"];
        } else {
            $order = 'user_fullname';
            $dir = 'ASC';
        }//End of if else
        $filter['status'] = ['$ne' => 0]; 
                
        $totalData = $this->users_model->get_total_rows($filter);
        $totalFiltered = $totalData;

        if (empty($this->input->post("search")["value"])) {
            $records = $this->users_model->all_rows($limit, $start, $order, $dir, $filter);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->users_model->get_search_rows($limit, $start, $search, $order, $dir, $filter);
        }//End of if else
        $data = array();
        if (!empty($records)) {
            foreach ($records as $rows) { 
                $uservices = '';
                if(isset($rows->user_services) && is_array($rows->user_services)) {
                    foreach($rows->user_services as $uService) {
                        $uservices = $uservices.'<br>'.$uService->service_name;
                    }//End of foreach()
                }//End of if
                            
                $nestedData["user_fullname"] = $rows->user_fullname;
                $nestedData["login_username"] = $rows->login_username;
                $nestedData["mobile_number"] = $rows->mobile_number;
                $nestedData["user_services"] = $uservices;
                $data[] = $nestedData;
            }
        }//End of if
        $json_data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
    }//End of get_records()

}//End of Allapps