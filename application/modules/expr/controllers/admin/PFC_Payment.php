<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class PFC_Payment extends Rtps {
 /**
  * __construct
  *
  * @return void
  */
  function __construct() {
    parent::__construct();
    $this->load->model('admin/users_model');
    $this->load->model('intermediator_model');
    $this->config->load('rtps_services');

  }

  public function payment($rtps_trans_id){
    if(empty($rtps_trans_id)){
      redirect(base_url('expr/admin/my-transactions'));
    }

    $transaction_data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id);// pre($transaction_data);
    if(empty($transaction_data)){
        redirect(base_url('expr/admin/my-transactions'));
    }
    if($transaction_data->status !== "S"){
        redirect(base_url('expr/admin/my-transactions'));
    }
    if(!empty($transaction_data->pfc_payment_status)){
          redirect(base_url('expr/admin/my-transactions'));
    }
    $user=$this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
    $uniqid=uniqid();
    $DEPARTMENT_ID=$uniqid.time();
    $data['ApplicationStatus']=$transaction_data->status;
    $data['service_charge']=$this->config->item('service_charge');
    $data['scanning_charge']=$this->config->item('scanning_charge');
    $data['printing_charge']=$this->config->item('printing_charge');
    $data['rtps_trans_id']=$rtps_trans_id;
    $data['department_data']=array(
      "DEPT_CODE"=>$user->dept_code,
      "OFFICE_CODE"=>$user->office_code,
      "REC_FIN_YEAR"=>"2020-2021",//dynamic
      "HOA1"=>"",
      "FROM_DATE"=>"01/04/2020",
      "TO_DATE"=>"31/03/2099",
      "PERIOD"=>"O",// O for ontimee payment
      "CHALLAN_AMOUNT"=>"0",
      "DEPARTMENT_ID"=>$DEPARTMENT_ID,
      "MOBILE_NO"=>$user->mobile,//pfc no
      "SUB_SYSTEM"=>"REV-SP|".base_url('expr/admin/get/payment-response'),
      "PARTY_NAME"=>isset($transaction_data->applicant_name) ? $transaction_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
      "PIN_NO"=>isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005",
      "ADDRESS1"=>isset($transaction_data->address1) ? $transaction_data->address1 : "NIC",
      "ADDRESS2"=>isset($transaction_data->address2) ? $transaction_data->address2 : "",
      "ADDRESS3"=>isset($transaction_data->address3) ? $transaction_data->address3 : "781005",
      "MULTITRANSFER"=>"Y",
      "NON_TREASURY_PAYMENT_TYPE"=>"01",
      "TOTAL_NON_TREASURY_AMOUNT"=>$this->config->item('service_charge'),
      "AC1_AMOUNT"=>$this->config->item('service_charge'),
      "ACCOUNT1"=>$user->account1,
    );

    $this->load->view('includes/header');
    $this->load->view('admin/payment/pfc_payment',$data);
    $this->load->view('includes/footer');
  }
  public function retry_payment($rtps_trans_id){
    if(empty($rtps_trans_id)){
      redirect(base_url('expr/admin/my-transactions'));
    }

    $transaction_data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id);
    if(empty($transaction_data)){
        redirect(base_url('expr/admin/my-transactions'));
    }
    if($transaction_data->status !== "S"){
        redirect(base_url('expr/admin/my-transactions'));
    }

    //for failed only
    if($transaction_data->pfc_payment_status !== "N"){
          redirect(base_url('expr/admin/my-transactions'));
    }
    if(empty($transaction_data->payment_params)){
        redirect(base_url('expr/admin/my-transactions'));
    }
    $user=$this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
  //  pre($transaction_data->payment_params);
    $uniqid=uniqid();
    $DEPARTMENT_ID=$uniqid.time();
    $data['ApplicationStatus']=$transaction_data->status;
    $data['service_charge']=$this->config->item('service_charge');
    $data['scanning_charge']=$this->config->item('scanning_charge');
    $data['printing_charge']=$this->config->item('printing_charge');
    $data['rtps_trans_id']=$rtps_trans_id;
    $data['department_data']=(array)$transaction_data->payment_params;
    $data['department_data']['DEPARTMENT_ID']=$DEPARTMENT_ID;
  //  pre($data);
    $this->load->view('includes/header');
    $this->load->view('admin/payment/retry_pfc_payment',$data);
    $this->load->view('includes/footer');
  }

}
