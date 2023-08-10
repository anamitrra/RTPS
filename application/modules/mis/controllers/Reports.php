<?php

/**
 * Description of Mis
 *
 * @author Prasenjit Das
 *
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Reports extends frontend
{
  //put your code here
  public function __construct()
  {
    parent::__construct();
    
  }
  /**
   * index
   *
   * @return void
   */
  public function index()
  {
    $this->load->model("applications_model");
    
  }
  public function servicewise_status()
    {
        $this->load->model("api_model");
        $this->load->model("services_model");
        $received = $this->api_model->total_services_group_by_service();
        $delivered = $this->api_model->total_services_delivered_group_by_service();
        $timely_delivered = $this->api_model->check_timeline_for_all_delivered_services();
        $rejected = $this->api_model->total_services_rejected_group_by_service();
        $rejected_in_time = $this->api_model->rejected_in_time_all_services();
        $pit = $this->api_model->pending_in_time_applications();
        $services = $this->services_model->get_all([]);
        //pre($services);
        if (!is_array($received)) $received = (array)$received;
        if (!is_array($rejected)) $rejected = (array)$rejected;
        if (!is_array($delivered)) $delivered = (array)$delivered;
        if (!is_array($timely_delivered)) $timely_delivered = (array)$timely_delivered;
        if (!is_array($rejected_in_time)) $rejected_in_time = (array)$rejected_in_time;
        if (!is_array($pit)) $pit = (array)$pit;
        foreach ($received as $key => $r_val) {
            foreach ($delivered as $key => $d_val) {
                if ($d_val->_id == $r_val->_id) {
                    $r_val->delivered = isset($d_val->count) ? $d_val->count : 0;
                }
            }
            foreach ($rejected as $key => $re_val) {
                if ($re_val->_id == $r_val->_id) {
                    $r_val->rejected = isset($re_val->count) ? $re_val->count : 0;
                }
            }
            foreach ($timely_delivered as $key => $t_val) {
                if ($t_val->_id == $r_val->_id) {
                    $r_val->timely_delivered = isset($t_val->count) ? $t_val->count : 0;
                }
            }
            foreach ($rejected_in_time as $key => $rit_val) {
                if ($rit_val->_id == $r_val->_id) {
                    $r_val->rit = isset($rit_val->count) ? $rit_val->count : 0;
                }
            }
            foreach ($pit as $key => $pit_val) {
                if ($pit_val->_id == $r_val->_id) {
                    $r_val->pit = isset($pit_val->count) ? $pit_val->count : 0;
                }
            }
            if (!isset($r_val->rejected)) {
                $r_val->rejected = 0;
            }
            if (!isset($r_val->delivered)) {
                $r_val->delivered = 0;
            }
            if (!isset($r_val->timely_delivered)) {
                $r_val->timely_delivered = 0;
            }
            if (!isset($r_val->rit)) {
                $r_val->rit = 0;
            }
            if (!isset($r_val->pit)) {
                $r_val->pit = 0;
            }
            $r_val->pending = $r_val->total_received - ($r_val->delivered + $r_val->rejected);
            //$r_val->pit=$r_val->total_received-($r_val->timely_delivered+$r_val->rit);
        }

        foreach($services as $val){
            foreach($received as $r_val){
                ($val->service_id==$r_val->_id)?$r_val->service_name=$val->service_name:"";
            }
        }
        //pre($received);
        // return $this->output
        //     ->set_content_type('application/json')
        //     ->set_status_header(200)
        //     ->set_output(json_encode(array(
        //         'status' => TRUE,
        //         'data' => $received
        //     )));
        $emailBody = '<p>Dear Rahul Deka,</p>
        <p>Below is the status of applications in RTPS</p>';
        $emailBody=$this->load->view('reports/service_wise',array('data'=>$received),true);
        echo $emailBody;die;
        $email="prasn2009@gmail.com,prasenjit.89@supportgov.in";
        //$email="prasn2009@gmail.com,r.deka@nic.in,prasenjit.89@supportgov.in";        
        $this->load->library('pdf');
        $templateFileName='Application_status';
        $file=$this->pdf->save($emailBody,$templateFileName);
        
        $this->remail->sendemail($email, "Application Status", $emailBody,$file);
    }
}