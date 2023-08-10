<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Useractivities extends Rtps {

    public function __construct() {
        parent::__construct();
        $this->load->model('useractivities_model');
        $this->load->model('users_model');
    }//End of __construct()

    public function index() {
        $this->load->view('includes/header', array("pageTitle" => "Activity Logs"));
        $this->load->view('useractivities/activities_view');
        $this->load->view('includes/footer');
    }//End of index()

    public function test() {
        $activity_data = array(
            'user_id' => $this->session->userId->{'$id'},
            'activity_title' => 'Test activity',
            'activity_description' => 'Test activity',
            'activity_type' => 1, //1 for insert new 2 for update
            'data_before_update' => array(),
            'data_after_update' => array(),
            'activity_time' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
        );
        $this->useractivities_model->insert($activity_data);
        pre($activity_data);
    }//End of test()

}//End of Useractivities
