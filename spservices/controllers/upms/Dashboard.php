<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Upms {

    public function __construct() {
        parent::__construct();
        $this->isloggedin();
        $this->load->model('upms/applications_model'); 
        $this->load->helper("appstatus");
    }//End of __construct()
  
    public function index() {
        $filter = array(
            "current_users.login_username" => $this->session->loggedin_login_username,
            "service_data.service_id" => array('$in'=>$this->session->loggedin_user_service_code),
        );
        $additional_role_codes = $this->session->additional_role_codes ?? array();
        if (count($additional_role_codes)) {
            $filter['current_users.user_level_no'] = $this->session->loggedin_user_level_no??0;
        }
        $received = $this->applications_model->get_rows_count($filter);
        
        $filterQueried = array_merge($filter, ["service_data.appl_status"=>"QS"]);
        $queried = $this->applications_model->get_rows_count($filterQueried);
        
        $filterDelivered = array_merge($filter, ["service_data.appl_status"=>"D"]);
        $delivered = $this->applications_model->get_rows_count($filterDelivered);
        
        $filterRejected = array_merge($filter, ["service_data.appl_status"=>"R"]);
        $rejected = $this->applications_model->get_rows_count($filterRejected);
        
        $pending = $received-($delivered+$rejected);
        
        $progress = $received?(($delivered+$rejected+$queried)/$received)*100:0;
        
        $data = array(
            "received" => $received,
            "pending" => $pending,
            "queried" => $queried,
            "delivered" => $delivered,
            "rejected" => $rejected,
            "progress" => $progress
        );
        
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : My Dashboard", "page" => "dashboard"]);
        $this->load->view('upms/dashboard_view', $data);
        $this->load->view('upms/includes/footer');
    }//End of index()
    
    public function appsbystatus($appl_status=null) {
        $filter = array(
            "current_users.login_username" => $this->session->loggedin_login_username,
            "service_data.service_id" => array('$in'=>$this->session->loggedin_user_service_code)
        );
        $additional_role_codes = $this->session->additional_role_codes ?? array();
        if (count($additional_role_codes)) {
            $filter['current_users.user_level_no'] = $this->session->loggedin_user_level_no??0;
        }
        if(strlen($appl_status)) {
            if($appl_status == 'p') {
                //db.sp_applications.find({"service_data.appl_status":{$nin:["D","R"]}})
                $filter["service_data.appl_status"] = array('$nin'=>array("D","R"));
            } else {
                $filter["service_data.appl_status"] = strtoupper($appl_status);
            }//End of if else            
        }//End of if else
        $applStatus = getstatusname(strtoupper($appl_status));
        $header_title = "My Applications : {$applStatus}";
        $data = array(
            'appl_status' => $appl_status,
            'card_title' => $header_title,
            'myapplications' => $this->applications_model->get_rows($filter)
        );         
        $this->load->view('upms/includes/header', ["header_title" => $header_title, "page" => $appl_status]);
        $this->load->view('upms/myapplications_view', $data);
        $this->load->view('upms/includes/footer');
    }//End of appsbystatus()
}//End of Dashboard