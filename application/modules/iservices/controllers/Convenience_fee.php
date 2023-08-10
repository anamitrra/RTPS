<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Convenience_fee extends Rtps {
 /**
  * __construct
  *
  * @return void
  */
  function __construct() {
    parent::__construct();
    $this->load->model('admin/users_model');
    $this->load->model('intermediator_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    if(!empty($this->session->userdata('role'))){
      //  $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
      redirect(base_url('iservices/admin/my-transactions'));
      }else{
        $this->slug = "user";
      }

  }

  private function is_admin(){
    $user=$this->session->userdata();
    if(isset($user['role']) && !empty($user['role'])){
      return true;
    }else{
      return false;
    }
  }
  private function my_transactions(){
    $user=$this->session->userdata();
    if(isset($user['role']) && !empty($user['role'])){
      redirect(base_url('iservices/admin/my-transactions'));
    }else{
      redirect(base_url('iservices/transactions'));
    }
  }
  private function check_payment_status($DEPARTMENT_ID=null){

   
    if($DEPARTMENT_ID){
      $min=$this->intermediator_model->checkPaymentIntitateTime($DEPARTMENT_ID);
      // pre( $min);
      if( $min !== 'N' && $min < 6){
        $this->session->set_flashdata('errmessage', 'Please verify payment status after 5 minutes');
        $this->my_transactions();
        return;
      }
      $grndata=$this->checkgrn($DEPARTMENT_ID);
      if(!empty($grndata)){
          if($grndata['STATUS'] === 'Y'){
            $this->my_transactions();
            return;
          }
          $ar=array('N','A');
          if(!empty($grndata['GRN']) && !in_array($grndata['STATUS'] , $ar) ){
            $this->my_transactions();
            return;
          }
      }
      
      // $ref=modules::load('iservices/basundhara/Basundahara_response');
      // $ref->checkgrn($DEPARTMENT_ID);
    }

  }
  public function payment($rtps_trans_id){ 
    $financial_year=get_financial_year();
    $app_status=false;
    if(empty($rtps_trans_id)){
      $this->my_transactions();
    }

    $transaction_data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id); //pre($transaction_data);
   
    if(empty($transaction_data)){
      $this->my_transactions();
    }
    
    if (property_exists($transaction_data,"pfc_payment_status")  ){
      $app_status="S";
      if($transaction_data->pfc_payment_status === "Y"){
        $this->my_transactions();
      }
      
       
    }else{
      if($transaction_data->status !== "S"){
        $this->my_transactions();
      }
    }
    
    $dept_code="ARI";//$guidelines->dept_code;
    $office_code="ARI000";//$guidelines->office_code;
    if(property_exists($transaction_data,"department_id") && property_exists($transaction_data,"payment_params") ){
      $this->check_payment_status($transaction_data->department_id);
    }
    
    $rtps_account=$this->config->item("rtps_account");
    $rtps_convenience_fee=$this->config->item("rtps_convenience_fee");
    //for citizen
    $uniqid=uniqid();
    $DEPARTMENT_ID=$uniqid.time();
    $data['rtps_trans_id']=$rtps_trans_id;
    $data['rtps_convenience_fee']=$rtps_convenience_fee;
    $data['department_data']=array(
        "DEPT_CODE"=>$dept_code,//$user->dept_code,
        "OFFICE_CODE"=>$office_code,//$user->office_code,
        "REC_FIN_YEAR"=> $financial_year['financial_year'],//dynamic
        "HOA1"=>"",
        "FROM_DATE"=> $financial_year['from_date'],
        "TO_DATE"=>"31/03/2099",
        "PERIOD"=>"O",// O for ontimee payment
        "CHALLAN_AMOUNT"=>"0",
        "DEPARTMENT_ID"=>$DEPARTMENT_ID,
        "MOBILE_NO"=>$this->session->userdata('mobile'),
        "SUB_SYSTEM"=>"ARTPS-SP|".base_url('iservices/convenience_response/payment_response'),
        "PARTY_NAME"=>isset($transaction_data->applicant_name) ? $transaction_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
        "PIN_NO"=>isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005",
        "ADDRESS1"=>isset($transaction_data->address1) ? $transaction_data->address1 : "NIC",
        "ADDRESS2"=>isset($transaction_data->address2) ? $transaction_data->address2 : "",
        "ADDRESS3"=>isset($transaction_data->address3) ? $transaction_data->address3 : "781005",
        "MULTITRANSFER"=>"Y",
        "NON_TREASURY_PAYMENT_TYPE"=>"02",
        "TOTAL_NON_TREASURY_AMOUNT"=> $rtps_convenience_fee,
        "AC1_AMOUNT"=>$rtps_convenience_fee,
        "ACCOUNT1"=> $rtps_account
      );

    $res=$this->update_pfc_payment_amount($data);
    if($res){
      $this->load->view('basundhara/payment',$data);
    }else{
      $this->my_transactions();
    }
    
  }

 
  public function update_pfc_payment_amount($data){
      $payment_params=$data['department_data'];
      $rtps_trans_id=$data['rtps_trans_id'];
      $data_to_update=array('department_id'=>$payment_params['DEPARTMENT_ID'],
                            'payment_params'=>$payment_params);
     
      $data_to_update['rtps_convenience_fee']=$data['rtps_convenience_fee'];
     
      $result=$this->intermediator_model->update_payment_status($rtps_trans_id,$data_to_update);
      // pre($result->getMatchedCount());
         if($result->getMatchedCount()){
           $this->load->model('admin/pfc_payment_history_model');
           $data_to_update['rtps_trans_id']=$rtps_trans_id;
           $data_to_update['createdDtm']=new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
           $this->pfc_payment_history_model->insert($data_to_update);
          return true;
         }else {
          return false;
         }
  }

  public function checkgrn($DEPARTMENT_ID = null)
  {
    $transaction_data = $this->intermediator_model->get_row(array('department_id' => $DEPARTMENT_ID));
    if ($DEPARTMENT_ID) {
      $OFFICE_CODE = $transaction_data->payment_params->OFFICE_CODE;
      $am1 = isset($transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
      $am2 = isset($transaction_data->payment_params->CHALLAN_AMOUNT) ? $transaction_data->payment_params->CHALLAN_AMOUNT : 0;
      $AMOUNT = $am1 + $am2;
      $string_field = "DEPARTMENT_ID=" . $DEPARTMENT_ID . "&OFFICE_CODE=" . $OFFICE_CODE . "&AMOUNT=" . $AMOUNT;
      $url = $this->config->item('egras_grn_cin_url');
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 3);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $string_field);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      // curl_setopt ($ch, CURLOPT_CAINFO, dirname(FILE)."/123.assam.gov.in.crt");
      curl_setopt($ch, CURLOPT_FAILONERROR, FALSE);
      curl_setopt($ch, CURLOPT_NOBODY, false);
      $result = curl_exec($ch);
      curl_close($ch);
      $res = explode("$", $result); 
    
      if ($res) {
        $STATUS = isset($res[16]) ? $res[16] : '';
        $GRN = isset($res[4]) ? $res[4] : '';
        $this->intermediator_model->update_row(
          array('department_id' => $DEPARTMENT_ID),
          array(
            "pfc_payment_response.GRN" => $GRN,
            "pfc_payment_response.AMOUNT" => isset($res[6]) ? $res[6] : '',
            "pfc_payment_response.PARTYNAME" => isset($res[18]) ? $res[18] : '',
            "pfc_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
            "pfc_payment_response.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
            "pfc_payment_response.BANKNAME" => isset($res[22]) ? $res[22] : '',
            "pfc_payment_response.BANKCODE" => isset($res[8]) ? $res[8] : '',
            "pfc_payment_response.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
            "pfc_payment_response.STATUS" => $STATUS,
            "pfc_payment_response.PRN" => isset($res[12]) ? $res[12] : '',
            "pfc_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
            "pfc_payment_response.BANKCIN" => isset($res[10]) ? $res[10] : '',
            'pfc_payment_status' => $STATUS
          )
        );
        return array(
          'GRN'=>$GRN,
          'STATUS'=>$STATUS
        );
      }
    }else{
      return false;
    }
   
  }


}
