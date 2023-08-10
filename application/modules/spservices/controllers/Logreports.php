<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Logreports extends Rtps {

    public function __construct() {
        parent::__construct();
        $this->load->model('logreports_model');
    }//End of __construct()

    public function index() {
        /*$data = array(
            "log_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "log_msg" => "Test message",
            "log_status" => 2
        );
        $this->logreports_model->insert($data);*/
        $this->load->view('includes/header');
        $this->load->view('logreports_view');
        $this->load->view('includes/footer');        
    }//End of index()
  
    public function deleteme($objId=null) {
        if($this->checkObjectId($objId)) {
            $this->logreports_model->delete_by_filter(['_id' => new ObjectId($objId)]);
            $this->session->set_flashdata('flashMsg','One recodr has been successfully deleted');
        } else {
            $this->session->set_flashdata('flashMsg','Invalid object id');
        }//End of if else
        redirect('spservices/logreports');
    }//End of deleteme()
    
    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Logreports