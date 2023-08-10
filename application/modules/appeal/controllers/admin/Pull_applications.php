<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Pull_applications extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        if(!in_array($this->session->userdata('role')->slug,['RA','RR'])){
            redirect('appeal/dashboard');
        }
    }
    function index()
    {
        $user_location = $this->session->userdata('location');
        $userId = $this->session->userdata('userId');
        $role=$this->session->userdata('role')->slug;
        // pre();
        $this->load->model('pull_applications_model');
        $pending_applications = $this->pull_applications_model->pending_appeals($user_location);
        // pre($pending_applications);

        if(!empty($pending_applications)){
            foreach($pending_applications as $key => $value){
                $id=new ObjectId($value->{'_id'}->{'$id'});
                $filter=[
                    '_id'=>$id,
                    'process_users.role_slug'=>$role
                ];
                $set=[
                    'process_users.$.userId'=>new ObjectId($userId->{'$id'})
                ];
                $this->pull_applications_model->update_where($filter,$set);
            }
        }
        $this->load->view('includes/header', array("pageTitle" => "Dashboard | Pull Applications"));
        $this->load->view('pull_applications/pull_application_success');
        $this->load->view('includes/footer');
      
    }
}
