<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Alerts extends rtps
{
        /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function check_appeal_if_30_days_crossed()
    {
        $this->load->model('appeals_model');
        $appeals=$this->appeals_model->appeal_timeline_check(30);
        //pre($appeals);
        $this->lock_appeals($appeals);
    }
    public function lock_appeals($appeals)
    {
        $this->load->model('alerts_model');
        $this->alerts_model->set_collection("appeal_applications");
        foreach($appeals as $key => $value){
            $data['locked_status']=true;
            $this->alerts_model->update($value->{'_id'}->{'$id'},$data);
        }
    }
    public function check_appeal_if_23_days_crossed()
    {
        $this->load->model('appeals_model');
        $appeals=$this->appeals_model->appeal_timeline_check(23);
        pre($appeals);
        $this->lock_appeals($appeals);
    }

}